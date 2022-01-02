<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Http\Resources\ContractResource;
use App\Jobs\CreateServiceIndexRecord;
use App\Models\Bed;
use App\Models\Floor;
use App\Models\Room;
use App\Models\Room_type;
use App\Traits\TraitResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class RoomController extends Controller
{
    use TraitResponse;
    public  $room_id;
    public function __construct($room_id = 0)
    {
        $this->room_id = $room_id;
    }
    public function index(Request $request)
    {
        $this->middleware('can:room-list');
        $models = Room::filter($request->only(['floor_id']))->with(['room_type', 'beds', 'beds.contract'])->get();
        return $this->responseApi($models, 200);
    }


    public function updateOrCreate(RoomRequest $request)
    {

        if ($request->route('room')) {
            $model = Room::find($request->route('room'));
            if (!$model) {
                return $this->responseApi('Đường dẫn không tồn tại', 404);
            }
            $model->fill($request->all());
            $model->floor_id = $request->floor_id;
            $model->room_type_id = $request->room_type_id;
            $model->save();
        } else {
            $model = new Room();
            $model->fill($request->all());
            $model->floor_id = $request->floor_id;
            $model->room_type_id = $request->room_type_id;
            $model->save();

            $this->room_id = $model->id;
            CreateServiceIndexRecord::dispatch($model->id);
        }

        return $model;
    }

    public function store(RoomRequest $request)
    {
        $this->middleware('can:room-add');

        // return $this->responseApi($data, 200, 'Cập nhật thành công');
        try {
            DB::beginTransaction();
            $model = new Room();
            $model->fill($request->all());
            $model->floor_id = $request->floor_id;
            $model->room_type_id = $request->room_type_id;
            $model->save();
            $room_type = Room_type::find($request->room_type_id);
            $numberBedOfRoom = $room_type->number_bed;
            $this->room_id = $model->id;
            CreateServiceIndexRecord::dispatch($model->id);
            for ($i = 1; $i <= $numberBedOfRoom; $i++) {
                Bed::create([
                    'name' => "G_" . $i,
                    'room_id' => $model->id,
                ]);
            }
            DB::commit();
            return $this->responseApi("", 200, 'Thêm thành công');
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->responseApi("", 400, 'Thêm mới thất bại !' . $exception);
        }
    }

    public function update(RoomRequest $request)
    {
        $this->middleware('can:room-edit');
        $data = $this->updateOrCreate($request);

        return $this->responseApi($data, 200, 'Cập nhật thành công');
    }

    /*
        Khi tạo phòng hàng loạt, 
        Cần những trường sau id tầng, id loại phòng, tổng số phòng, 
        tiền tố tên phòng (ví dụ : p001 thì p0 là tiền tố mã phòng), 
        số phòng bắt đầu từ (nếu tầng hiện tại có 10 phòng thì số phòng bắt đầu từ 11), 
        tổng số giường (lấy từ loại phòng), tiền tố tên giường (ví dụ g001 - thì g0 là tiền tố), 
        vị trí bắt đầu từ(nếu giường hiện tại có 10 phòng thì số giường bắt đầu từ 11)
    */
    public function createMultiRoom(Request $request)
    {
        $this->middleware('can:room-add');
        $numberRoom = $request->numberRoom;
        $preRoomName = $request->preName;

        $floor = Floor::find($request->floor_id);
        $room_type = Room_type::find($request->room_type_id);

        $mess = '';

        if (trim($preRoomName) == '') {
            $mess = 'Mời nhập tiền tố cho phòng';
            return $this->responseApi($data = [], 401, $mess);
        }

        if (!$floor || !$room_type) {
            $mess = 'Tầng hoặc loại phòng không tồn tại';
            return $this->responseApi($data = [], 401, $mess);
        }

        $numberRoomOfFLoor = $floor->load('rooms')->rooms->count();
        $numberBedOfRoom = $room_type->number_bed;

        for ($i = 0; $i < $numberRoom; $i++) {
            $model = new Room();
            $model->floor_id = $request->floor_id;
            $model->room_type_id = $request->room_type_id;
            $model->name = $preRoomName . ($numberRoomOfFLoor + $i + 1);
            $model->save();
            for ($j = 0; $j < $numberBedOfRoom; $j++) {
                $bed = new Bed();
                $bed->room_id = $model->id;
                $bed->name = 'G_' . ($j + 1);
                $bed->save();
            }
        }
        return $this->responseApi($data = [], 200, 'Tạo phòng thành công: ' . $numberRoom . ' Phòng');
    }

    public function show($id)
    {
        $this->middleware('can:room-detail');
        $model = Room::with(['beds', 'beds.contract'])->find($id);

        if (!$model) {
            return $this->responseApi([], 404, 'Đường dẫn không tồn tại');
        }

        return new ContractResource($model);
    }

    public function destroy($id)
    {
        $this->middleware('can:room-delete');
        $model = Room::find($id);

        if (!$model) {
            return $this->responseApi([], 404, 'Đường dẫn không tồn tại');
        }
        $contracts = $model->contracts;
        foreach ($contracts as $contract) {
            if ($contract != null) {
                $now = Carbon::now();
                $dayDiff = $now->diffInDays($contract->end_day);
                if ($contract == null || $dayDiff >= 0) {
                    return $this->responseApi([], 403, 'Phòng đang sử dụng hợp đồng. Không thể xóa');
                }
            }
        }
        try {
            DB::beginTransaction();

            $model->service_index->each(function ($s) {
                $s->delete();
            });
            $model->beds->each(function ($bed) {
                $bed->delete();
            });


            $model->delete();
            DB::commit();

            return  $this->responseApi(
                "",
                200,
                'Xoá thành công'
            );
        } catch (\Throwable $th) {
            Log::info($th);
            return  $this->responseApi(
                $th,
                400,
                'Xoá thất bại'
            );
        }
    }
}
