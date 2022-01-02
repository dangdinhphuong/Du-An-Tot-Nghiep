<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Bed;
use App\Models\Building;
use App\Models\Floor;
use App\Models\InvoiceDetail;
use App\Models\Maintenance;
use App\Models\Project;
use App\Models\Receipt;
use App\Models\Room;
use App\Models\ServiceIndex;
use App\Models\User;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use TraitResponse;
    //Cài này chỉ để thử thôi nha
    public function index(Request $request)
    {
        $projects = Project::with('buildings:name,buildings.project_id', 'buildings.floors.beds')->get();
        return $this->responseApi(
            [
                'list_feild_projects' => $projects,
            ],
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }

    // Thống kê trạng thái dịch vụ đã xong
    public function contractStatus(Request $request)
    {
        $project = Project::filter(request()->only(['project_id']))->with([
            'buildings' => function ($query) {
                $query->select(['id', 'name', 'project_id']);
            },
            'buildings.floors' => function ($query) {
                $query->select(['id', 'name', 'building_id']);
                $query->withCount(
                    [
                        'rooms as total_rooms',
                        'rooms as room_has_contract' => function ($query) {
                            $query->has('contracts');
                        },
                        'rooms as room_doesnt_contract' => function ($query) {
                            $query->doesntHave('contracts');
                        },
                    ]
                );
            },
            'buildings.floors.rooms' => function ($query) {
                $query->select(['id', 'name', 'floor_id']);
                $query->withCount(
                    [
                        'beds as total_beds',
                        'beds as bed_has_contract' => function ($query) {
                            $query->has('contract');
                        },
                        'beds as bed_doesnt_contract' => function ($query) {
                            $query->doesntHave('contract');
                        },
                    ]
                );
            }
        ])->select(['id', 'name'])->paginate(10);
        return $this->responseApi(
            [
                'projects' => $project,
            ],
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }

    // Thống kê sinh viên theo hợp đồng và dịch vụ
    public function studentRent(Request $request)
    {
        //Phần này chưa lấy được toà nhà và  địa chỉ
        $student = User::filter(request()->only(['email', 'id', 'CCCD', 'phone', 'name']))->where('user_type', '=', 0)->with([
            'contract' => function ($query) {
                $query->select(['id', 'deposit', 'start_day', 'user_id', 'room_id', 'bed_id']);
            },
            'contract.invoices' => function ($query) {
                $query->select(['id', 'contract_id']);
            },


        ])->paginate(10);
        return $this->responseApi(
            [
                'students' => $student,
            ],
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }

    // lịch sử hợp đồng đang có và đã có
    public function historyContract(Request $request)
    {

        $history = Bed::filter(request()->only(['project_id', 'phone', 'first_name', 'last_name']))->with([
            'room' => function ($query) {
                $query->select(['id', 'name', 'floor_id']);
            },
            'room.floor' => function ($query) {
                $query->select(['id', 'name', 'building_id']);
            },
            'room.floor.building' => function ($query) {
                $query->select(['id', 'name', 'project_id']);
            },
            'room.floor.building.project' => function ($query) {
                $query->select(['id', 'name']);
            },
            'contract',
            'contract.user' => function ($query) {
                $query->select(['id', 'first_name', 'last_name', 'phone']);
            }
        ])->has('contract')->select(['id', 'name', 'room_id'])->paginate(10);
        return $this->responseApi(
            [
                'history' => $history,
            ],
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }

    public function reportReceipt(Request $request)
    {
        $receipt = Receipt::filter($request->only(['project_id', 'note', 'collection_date', 'payment_type']))->with(
            [
                'receiptsReason' => function ($query) {
                    $query->select('id', 'title');
                },

                'users' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                },
            ]
        )->select(['id', 'collection_date', 'amount_of_money', 'payment_type', 'note', 'receipt_reason_id', 'created_at'])->paginate(10);
        return $this->responseApi(
            [
                'receipt' => $receipt,
            ],
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }

    public function reportServiceIndex(Request $request)
    {
        // todo : thêm tên dự án
        $serviceIndex = ServiceIndex::filter($request->only(['project_id', 'created_at']))->paginate(10);
        // return ($serviceIndex);
        $data['service_index'] = [];
        foreach ($serviceIndex as $s) {
            $projectServices = $s->room->floor->building->project->projectServices;
            foreach ($projectServices as $p) {
                if ($p->name == config('service_unit.electric.name')) {
                    $p->index = $s->index_electric;
                    $arr = [
                        'id' => $s->id,
                        'name' => $p->name,
                        'room' => [
                            'id' => $s->room->id,
                            'name' => $s->room->name,
                        ],
                        'project' => [
                            'id' => $s->room->floor->building->project->id,
                            'name' => $s->room->floor->building->project->name
                        ],
                        'index' => $s->index_electric,
                        'unit_price' => $p->unit_price,
                        'unit' => $p->unit,
                        'created_at' => $s->created_at,
                        'status' => $s->status ? 'Đã chốt chỉ số điện' : 'Chưa chốt chỉ số điện',
                        'total_money' => $p->unit_price * $s->index_electric,
                    ];
                    $data['service_index'][] = $arr;
                }
                if ($p->name == config('service_unit.water.name')) {
                    $p->index = $s->index_water;
                    $arr = [
                        'id' => $s->id,
                        'name' => $p->name,
                        'room' => [
                            'id' => $s->room->id,
                            'name' => $s->room->name,
                        ],
                        'project' => [
                            'id' => $s->room->floor->building->project->id,
                            'name' => $s->room->floor->building->project->name
                        ],
                        'index' => $s->index_water,
                        'unit_price' => $p->unit_price,
                        'unit' => $p->unit,
                        'created_at' => $s->created_at,
                        'status' => $s->status ? 'Đã chốt chỉ số nước' : 'Chưa chốt chỉ số nước',
                        'total_money' => $p->unit_price * $s->index_electric,
                    ];
                    $data['service_index'][] = $arr;
                }
            }
        }
        $data['first_page_url'] = $serviceIndex->path() . '?page=' . '1';
        $data['last_page'] = $serviceIndex->lastPage();
        $data['current_page'] =  $serviceIndex->currentPage();
        $data['last_page_url'] = $serviceIndex->path() . '?page=' . $serviceIndex->lastPage();
        $data['links'] = $serviceIndex->linkCollection();
        $data['next_page_url'] = $serviceIndex->nextPageUrl();
        $data['path'] = $serviceIndex->path();
        $data['per_page'] = $serviceIndex->perPage();
        $data['prev_page_url'] = $serviceIndex->previousPageUrl();
        $data['total'] = $serviceIndex->total();

        return $this->responseApi(
            $data,
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }
    public function reportProjectService(Request $request)
    {
        $invoiceDetails = InvoiceDetail::filter($request->only(['project_id']))->paginate();
        $data = [];
        $data['project_services'] = [];
        foreach ($invoiceDetails as $invoiceDetail) {
            $arr = [];
            $arr['invoice_detail'] = $invoiceDetail;
            $arr['invoice_detail']->status = $invoiceDetail->invoice->status ? 'Đã thu' : "Chưa thu";
            $arr['invoice_detail']->project_service_info = json_decode($arr['invoice_detail']->project_service_info);
            $arr['invoice_detail']->student_info = json_decode($arr['invoice_detail']->student_info);
            $arr['invoice_detail']->room_info = json_decode($arr['invoice_detail']->room_info);
            $arr['invoice_detail']->room_fee_info = json_decode($arr['invoice_detail']->room_fee_info);
            $data['project_services'][] = $arr;
        }
        $data['first_page_url'] = $invoiceDetails->path() . '?page=' . '1';
        $data['last_page'] = $invoiceDetails->lastPage();
        $data['current_page'] =  $invoiceDetails->currentPage();
        $data['last_page_url'] = $invoiceDetails->path() . '?page=' . $invoiceDetails->lastPage();
        $data['links'] = $invoiceDetails->linkCollection();
        $data['next_page_url'] = $invoiceDetails->nextPageUrl();
        $data['path'] = $invoiceDetails->path();
        $data['per_page'] = $invoiceDetails->perPage();
        $data['prev_page_url'] = $invoiceDetails->previousPageUrl();
        $data['total'] = $invoiceDetails->total();

        return $this->responseApi(
            $data,
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }
    // báo cáo tài sản và loại tài sản
    public function reportAsset(Request $request)
    {
        // filter(request()->only(['name']))->
        $asset = Asset::filter($request->only(['name', 'asset_type_id']))->with([
            'type_asset',
            'producer',
            'unit_asset',
        ])->select(['id', 'name', 'price', 'min_inventory', 'unit_asset_id', 'asset_type_id', 'producer_id'])
            ->groupByRaw('asset_type_id')
            ->paginate(10);
        return $this->responseApi(
            [
                'asset' => $asset,
            ],
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }

    // Báo cáo sửa chữa bảo trì
    public function reportMaintenace(Request $request)
    {
        $data['status'] = config('maintain.status');
        $asset = Maintenance::filter($request->only(['status']))->with([
            'userCreate' => function ($query) {
                $query->select('id', 'first_name', 'last_name');
            },
            'userUndertake' => function ($query) {
                $query->select('id', 'first_name', 'last_name');
            },
        ])->select(['id', 'name', 'type', 'note', 'date_start', 'date_end', 'user_create_id', 'user_undertake_id', 'status'])->paginate(10);
        // ->select(['id', 'name', 'note', 'created_at','user_create_id','user_undertake_id', 'status','cycle_date'])
        $data['maintain'] = $asset;
        return $this->responseApi(
            $data,
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }
}
