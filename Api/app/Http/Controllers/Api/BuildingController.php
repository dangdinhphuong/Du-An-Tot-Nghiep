<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;
use App\Traits\TraitResponse;
use App\Http\Resources\BuildingResource;
use App\Http\Requests\BuildingStoreRequest;
use App\Http\Requests\BuildingUpdateRequest;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BuildingController extends Controller
{
    use TraitResponse;
    public function index()
    {
        return $this->responseApi(
            Building::filter(request(['project_id']))->with(['floors'])->get(),
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }


    public function show($building_id)
    {
        $building =  Building::find($building_id);


        if ($building != null) {
            return $this->responseApi(
                new BuildingResource($building),
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

    public function storeOrUpdateForm()
    {
        if (request()->has('project_id')) {
            $data = Project::where('id', request('project_id'))->get(['id', 'name']);
        } else {
            $data = Project::all(['id', 'name']);
        }
        return $this->responseApi(
            $data,
            201,
            'Lấy dữ liệu thành công'
        );
    }

    public function store(BuildingStoreRequest $request)
    {
        $data = $request->except('_token');
        $result = Building::create($data);

        return $this->responseApi(
            new BuildingResource($result),
            201,
            'Thêm dữ liệu thành công'
        );
    }

    public function update(BuildingUpdateRequest $request, Building $building)
    {

        $inputs = $request->only(
            'name',
            'total_area',
            'note',
            'project_id',
        );

        $building->fill($inputs);
        $building->save();
        return $this->responseApi(
            new BuildingResource($building),
            200,
            'Sửa dữ liệu thành công'
        );
    }

    public function delete(Building $building)
    {

        if (!$building) {
            return $this->responseApi([], 404, 'Đường dẫn không tồn tại');
        }
        $floors = $building->floors;
        foreach ($floors as $f) {
            $rooms = $f->rooms;
            foreach ($rooms as $r) {
                $contracts = $r->contracts;

                if ($contracts != null) {
                    foreach ($contracts as $c) {
                        if ($c != null) {
                            $now = Carbon::now();
                            $dayDiff = $now->diffInDays($c->end_day);
                            if ($c == null || $dayDiff >= 0) {
                                return $this->responseApi([], 403, 'Tòa nhà đang sử dụng hợp đồng. Không thể xóa');
                            }
                        }
                    }
                }
            }
        }
        try {
            DB::beginTransaction();

            $building->floors->each(function ($floor) {
                $floor->rooms->each(function ($room) {
                    $room->service_index->each(function ($s) {
                        $s->delete();
                    });
                });
            });
            $building->floors->each(function ($floor) {
                $floor->rooms->each(function ($room) {
                    $room->beds->each(function ($bed) {
                        $bed->delete();
                    });
                });
            });

            $building->floors->each(function ($floor) {
                $floor->rooms->each(function ($room) {
                    $room->delete();
                });
            });
            $building->floors->each(function ($floor) {
                $floor->delete();
            });

            $building->delete();
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
