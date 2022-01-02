<?php

namespace App\Http\Controllers\Api\Staffs;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollectDepositRequest;
use App\Http\Requests\ContractStoreRequest;
use App\Http\Requests\ReceiptReason;
use App\Http\Resources\BedContractResource;
use App\Http\Resources\ContractForNormalCaseResource;
use App\Http\Resources\ContractResource;
use App\Http\Resources\StudentResource;
use App\Jobs\CreateContractHistoryInvoice;
use App\Jobs\CreateRoomHistoryInvoice;
use App\Jobs\SendContracWhenCreate;
use App\Models\Bed;
use App\Models\Bed_History;
use App\Models\Contract;
use App\Models\HistoryRent;
use App\Models\ProjectService;
use App\Models\Receipt;
use App\Models\Receipts_reason;
use App\Models\Room;
use App\Models\User;
use App\Traits\TraitResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContractController extends Controller
{
    use TraitResponse;
    public function __construct()
    {
        // $this->middleware('auth.jwt');
    }
    // TODO : get contract data with invoices , check where can delete contract
    public function index(Request $request)
    {

        // ::with(['contracts','floor','floor.buildings','floor.buildings.project'])->get()
        // dd(request('state'));
        $room = Bed::filter(request(['room_name', 'floor_id', 'building_id', 'project_id', 'room_id', 'deposit_state', 'not_exists', 'exists', 'first_name', 'last_name', 'email', 'phone', 'address', 'start_day', 'end_day']))->paginate(10);

        if (!is_null($room)) {
            return BedContractResource::collection($room);
        }


        return $this->responseApi([], 400, 'Không tìm thấy dữ liệu');
    }
    public function create(Request $request)
    {
        $bed = Bed::findOrFail($request->id);
        if ($bed->contract != null) {
            return $this->responseApi([], 400, 'Giường đã tạo hợp đồng');
        }
        $data['bed_data'] = new BedContractResource($bed);
        return $this->responseApi($data, 200, 'Lấy dữ liệu thành công');
    }
    public function store(ContractStoreRequest $request)
    {
        // start_day, end_day, price, deposit, note, room_id, bed_id, user_id, deposit_state, project_service_id, 
        $data = $request->only(['start_day', 'end_day', 'price', 'deposit', 'note', 'room_id', 'bed_id', 'user_id', 'deposit_state', 'project_service_id']);

        $data['project_service_id'] = json_encode($data['project_service_id']);
        try {
            DB::beginTransaction();
            $contract = Contract::create($data);
            $data = array_merge(
                $request->only(['user_id', 'room_id']),
                [
                    'contract_id' => $contract->id,
                    'date_rent' => Carbon::now(),
                    'state' => config('contract.history_rent.dang_thue')
                ]
            );
            HistoryRent::create($data);

            DB::commit();

            SendContracWhenCreate::dispatch($request->only(['start_day', 'end_day', 'price', 'deposit', 'note', 'room_id', 'bed_id', 'user_id', 'deposit_state', 'project_service_id']));
            CreateRoomHistoryInvoice::dispatch($request->input('room_id'));
            CreateContractHistoryInvoice::dispatch($contract->id);
            return $this->responseApi($contract, 201, 'Tạo hợp đồng thành công');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::info("Tạo hợp đồng cho sinh viên " . $data['user_id'] . ' tại thời điểm ' . Carbon::now() . ' thất bại ' . ' Người tạo : ' . auth()->user() ?? 'not found' . '. Lỗi : ' . $th);
            return $this->responseApi($th, 500, 'Tạo hợp đồng thất bại !');
        }
    }
    public function edit($id)
    {
        // todo : tra ve cac project_service duoc chon theo hop dong
        // todo : lam api lịch sử thu phí
        $contract = Contract::with(['user', 'bed', 'room'])->find($id);
        if ($contract == null) {
            return $this->responseApi([], 400, 'Không tìm thấy dữ liệu');
        }
        if ($contract->project_service_id != null) {
            $projectService = ProjectService::find(json_decode($contract->project_service_id));
            $contract->project_service  = $projectService;
        }
        // dd($projectService);
        if (!is_null($contract)) {
            // return new ContractForNormalCaseResource($contract);
            return $this->responseApi($contract, 200, 'Lấy dữ liệu thành công');
        }


        return $this->responseApi([], 400, 'Không tìm thấy dữ liệu');
    }
    public function collectDepositForm($id)
    {
        $contract = Contract::find($id);
        $receipt = Receipts_reason::all();
        $data['user']['id'] = $contract->user->id;
        $data['user']['name'] =   $contract->user->last_name . ' ' . $contract->user->first_name;
        $data['payment_type'] = ['Tiền mặt', 'Chuyển khoản'];
        $i = 0;
        foreach ($receipt as $r) {
            $data['receipt_reason'][$i]['id'] = $r->id;
            $data['receipt_reason'][$i]['name'] = $r->title;
            $i++;
        }
        if (!is_null($contract)) {
            return $this->responseApi($data, 200, 'Lấy dữ liệu thành công');
        }

        return $this->responseApi([], 400, 'Không tìm thấy dữ liệu');
    }
    public function collectDeposit(CollectDepositRequest $request, $id)
    {
        $contract = Contract::find($id);
        if (!is_null($contract)) {
            $data = $request->only(['collection_date', 'user_id', 'amount_of_money', 'payment_type', 'note', 'receipt_reason_id']);
            Receipt::create(array_merge(
                $data,
                [
                    'contract_id' => $contract->id
                ]
            ));
            $contract->deposit_state = config('contract.deposit_state.da_thu');
            $contract->save();
            return $this->responseApi($contract, 201, 'Cập nhật dữ liệu thành công');
        }

        return $this->responseApi([], 400, 'Không tìm thấy dữ liệu');
    }
    public function update(Request $receipt, $id)
    {
        $contract = Contract::find($id);
        if (!is_null($contract)) {
            $contract->price = $receipt->price;
            $contract->deposit = $receipt->deposit;

            $contract->save();
            return $this->responseApi($contract, 201, 'Cập nhật dữ liệu thành công');
        }
    }
    public function endContract($id)
    {
        $contract = Contract::find($id);
        if ($contract == null) {
            return $this->responseApi("", 404, "Không tìm thấy bản ghi");
        }
        $contract->delete();
        $room = $contract->room;
        $contracts = $room->contracts;
        if ($contracts->isEmpty()) {
            $room->roomHistoryInvoices()->delete();
        }
        // $room = $contract->room;

        return $this->responseApi("", 200, ' Hợp đồng kết thúc thành công');
    }
    public function updateDepositState()
    {
    }
    public function filterStudent(Request $request)
    {
        $data['students'] = StudentResource::collection(User::where('user_type', config('User.userType.student'))->doesntHave('contract')->filter(request(['name', 'email']))->paginate(10));
        return $data['students'];
    }
    public function indexEndContract(Request $request)
    {
        // dd($request->all());
        $contract = Contract::onlyTrashed()->filterTrash($request->only(['start_day', 'end_day', 'room_id', 'bed_id']))->paginate(10);
        // dd($contract);
        // them deleted_at vao resource done
        if (!is_null($contract)) {
            return ContractForNormalCaseResource::collection($contract);
        }
        return $this->responseApi([], 400, 'Không tìm thấy dữ liệu');
    }
    public function editEndContract($id)
    {
        $contract = Contract::onlyTrashed()->with(['user', 'bed', 'room'])->find($id);
        if ($contract == null) {
            return $this->responseApi([], 400, 'Không tìm thấy dữ liệu');
        }
        if ($contract->bed == null) {
            $contract->bed_has_deleted = Bed::onlyTrashed()->find($contract->bed_id);
        }
        if ($contract->room == null) {
            $contract->room_has_deleted = Room::onlyTrashed()->find($contract->room_id);
        }
        if ($contract->project_service_id != null) {
            $projectService = ProjectService::withTrashed()->find(json_decode($contract->project_service_id));
            $contract->project_service  = $projectService;
        }
        // dd($projectService);
        if (!is_null($contract)) {
            // return new ContractForNormalCaseResource($contract);
            return $this->responseApi($contract, 200, 'Lấy dữ liệu thành công');
        }


        return $this->responseApi([], 400, 'Không tìm thấy dữ liệu');
    }
    public function changeBed(Request $request, $id)
    {
        $contract = Contract::find($id);
        if ($contract == null) {
            return $this->responseApi("", 404, "Không tìm thấy bản ghi");
        }
        $building_id = $contract->room->floor->building->id;
        $bed = Bed::whereHas('room.floor.building', function ($query) use ($building_id) {
            $query->where('id', $building_id);
        })->doesntHave('contract')->with(
            [
                'room' => function ($query) {
                    $query->select('id', 'name', 'floor_id');
                },
                'room.floor' => function ($query) {
                    $query->select('id', 'name', 'building_id');
                },
                'room.floor.building' => function ($query) {
                    $query->select('id', 'name');
                }
            ]
        )->get();
        return $this->responseApi($bed, 200, 'Lấy dữ liệu thành công');
    }
    public function moveBed(Request $request, $id)
    {
        $contract = Contract::find($id);
        if ($contract == null) {
            return $this->responseApi("", 404, "Không tìm thấy hợp đồng");
        }
        $bed = Bed::find($request->input('bed_id'));
        if ($bed == null) {
            return $this->responseApi("", 404, "Không tìm thấy giường");
        }
        $oldBuildingId = $contract->room->floor->building->id;
        $newBuildingId = $bed->room->floor->building->id;
        if ($oldBuildingId != $newBuildingId) {
            return $this->responseApi("", 400, "Giường khác tòa nhà");
        }
        if ($bed->id == $contract->bed_id) {
            return $this->responseApi("", 400, "Đây là giường sinh viên đang ở");
        }
        $bedContract = $bed->contract;
        if ($bedContract != null) {
            return $this->responseApi("", 400, "Giường đã có hợp đồng");
        }
        $contract->bed_id = $bed->id;
        $contract->room_id = $bed->room_id;
        $contract->save();
        Bed_History::create(array_merge(
            $request->only(['bed_id']),
            [
                'room_id' => $bed->room_id,
                'contract_id' => $contract->id,
                'day_transfer' => Carbon::now()->format('y-m-d h-i-s')
            ]
        ));
        return $this->responseApi("", 200, "Cập nhật thành công");
    }
}
