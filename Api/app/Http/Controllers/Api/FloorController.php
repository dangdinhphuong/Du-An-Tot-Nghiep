<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Floor;
use App\Traits\TraitResponse;
use App\Http\Resources\FloorResource;
use App\Http\Requests\FloorStoreRequest;
use App\Http\Requests\FloorUpdateRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FloorController extends Controller
{
    use TraitResponse;
    public function index(Request $request)
    {
        return $this->responseApi(
            Floor::filter(request(['name', 'building_id']))->with(['rooms', 'rooms.beds'])->get(),
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }


    public function show($floor_id)
    {
        $floor =
            Floor::with(['rooms', 'rooms.beds'])->find($floor_id);


        if ($floor != null) {
            return $this->responseApi(
                new FloorResource($floor),
                200,
                'Lấy dữ liệu thành công'
            );
        } else {

            return $this->responseApi(
                "",
                400,
                'Dữ liệu này không tồn tại ở bảng này.'
            );
        }
    }

    public function store(FloorStoreRequest $request)
    {
        $data = $request->except('_token');
        $result = Floor::create($data);

        return $this->responseApi(
            new FloorResource($result),
            201,
            'Thêm tầng thành công'
        );
    }

    public function update(FloorUpdateRequest $request, Floor $floor)
    {
        $inputs = $request->only(
            'name',
            'total_area',
            'building_id',
        );

        $floor->fill($inputs);
        $floor->save();
        return $this->responseApi(
            new FloorResource($floor),
            200,
            'Sửa dữ liệu thành công'
        );
    }

    public function delete(Floor $floor)
    {
        if (!$floor) {
            return $this->responseApi([], 404, 'Đường dẫn không tồn tại');
        }
        $rooms = $floor->rooms;
        foreach ($rooms as $r) {
            $contracts = $r->contracts;
            if ($contracts != null) {
                foreach ($contracts as $c) {
                    if ($c != null) {
                        $now = Carbon::now();
                        $dayDiff = $now->diffInDays($c->end_day);
                        if ($c == null || $dayDiff >= 0) {
                            return $this->responseApi([], 403, 'Tầng đang sử dụng hợp đồng. Không thể xóa');
                        }
                    }
                }
            }
        }

        try {
            DB::beginTransaction();

            $floor->rooms->each(function ($room) {
                $room->service_index->each(function ($s) {
                    $s->delete();
                });
            });
            $floor->rooms->each(function ($room) {
                $room->beds->each(function ($bed) {
                    $bed->delete();
                });
            });

            $floor->rooms->each(function ($room) {
                $room->delete();
            });

            $floor->delete();
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
        return  $this->responseApi(
            "",
            200,
            'Xoá thành công'
        );
    }
}
