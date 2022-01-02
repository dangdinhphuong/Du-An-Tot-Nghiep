<?php

namespace App\Http\Controllers\Api\Staffs;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterServiceIndexRequest;
use App\Http\Requests\ServiceIndexUpdateRequest;
use App\Models\ServiceIndex;
use App\Traits\TraitResponse;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceIndexController extends Controller
{
    //
    use TraitResponse;
    public function __construct()
    {
        $this->middleware('auth.jwt');
    }
    public function index(FilterServiceIndexRequest $request)
    {
        $data['month'] = [];
        $data['year'] = Carbon::now()->format('Y');
        foreach (config('Months.months') as $t) {
            $data['month'][] = $t;
        }
        if ($request->has('month') && $request->has('year')) {
            $insertDate = $request->input('month') . '-' . $request->input('year');
            $modInsertDate = date('Y-m-d H:i:s', strtotime($insertDate));
        } else {
            $modInsertDate = null;
        }

        $data['service'] = ServiceIndex::has('room.contracts')->date($modInsertDate)->filter($request->only(['project_id', 'building_id', 'floor_id', 'room_name', 'status']))->with(
            [
                'room' => function ($query) {
                    $query->select('id', 'name', 'floor_id');
                }, 'room.floor' => function ($query) {
                    $query->select('id', 'name', 'building_id');
                }, 'room.floor.building' => function ($query) {
                    $query->select('id', 'name', 'project_id');
                }, 'room.floor.building.project' => function ($query) {
                    $query->select('id', 'name');
                }
            ]
        )->paginate(10);
        return $this->responseApi($data, 200, 'Lấy dữ liệu thành công');
    }
    public function edit(Request $request, $id)
    {
        $data['service_index'] = ServiceIndex::where('id', $id)->with(['room'])->first();

        if (!isset($data['service_index']) && empty($data['service_index'])) {
            return $this->responseApi('', 404, 'Đường dẫn không tồn tại');
        }
        $previousMonth = Carbon::parse($data['service_index']->created_at)->subMonth();
        $modInsertDate =  date('Y-m-d H:i:s', strtotime($previousMonth));
        $serviceIndexsPrevious = ServiceIndex::where('created_at', 'like', '%' . $modInsertDate . '%')->where('room_id', $data['service_index']->room_id)->first();
        $data['service_index_prev'] = $serviceIndexsPrevious;
        return $this->responseApi($data, 200, 'Lấy dữ liệu thành công');
    }
    public function store(Request $request)
    {
        $currentYear =  Carbon::now()->format('Y');
        foreach (config('Months.months') as $month) {
            $insertDate = $month . '-' . $currentYear;
            $modInsertDate = date('Y-m-d H:i:s', strtotime($insertDate));
            $exitsRecord = DB::table('service_indexes')->where('created_at', $modInsertDate)->where('room_id', $request->room_id)->get();
            if ($exitsRecord->isNotEmpty()) {
                Log::warning("Attempt to insert the same service index for room " . $request->room_id . ' and created_at ' . $modInsertDate . ' at ' . Carbon::now());
            } else {
                DB::table('service_indexes')->insert(
                    [
                        'room_id' => $request->room_id,
                        'status' => config('Months.status.chua_chot'),
                        'created_at' => $modInsertDate
                    ]
                );
            }
        }
    }
    public function update(ServiceIndexUpdateRequest $request, $id)
    {
        $maintain = ServiceIndex::find($id);
        if ($maintain == null) {
            return $this->responseApi("", 404, "Không tìm thấy bản ghi");
        }
        if ($maintain->status == config('Months.status.chua_chot')) {
            $maintain->update(array_merge(
                $request->only(['index_water', 'index_electric', 'note', 'img']),
                [
                    'status' => config('Months.status.da_chot')
                ]
            ));
        } else {
            return $this->responseApi([], 400, 'Chỉ số đã chốt . Không được sửa');
        }
        return $this->responseApi($maintain, 201, 'Cập nhật thành công');
    }
}
