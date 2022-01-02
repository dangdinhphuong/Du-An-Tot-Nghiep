<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Traits\TraitResponse;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectCollection;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    use TraitResponse;
    public function index(Request $request)
    {

        $project = Project::with(['buildings'])->get();

        return $this->responseApi(
            ProjectResource::collection($project),
            200,
            "Lấy dữ liệu thành công"
        );
    }


    public function show($project_id)
    {
        $project = Project::find($project_id);


        if ($project != null) {
            return $this->responseApi(
                new ProjectResource($project),
                200,
                'Lấy dữ liệu thành công'
            );
        } else {

            return $this->responseApi(
                "",
                'Dữ liệu này không tồn tại ở bảng này.',
                400
            );
        }
    }

    public function store(ProjectStoreRequest $request)
    {
        $data = $request->except('_token');
        $result = Project::create($data);
        return $this->responseApi(
            new ProjectResource($result),
            201,
            'Thêm dữ liệu thành công'
        );
    }

    public function update(ProjectUpdateRequest $request, Project $project)
    {

        $inputs = $request->only(
            'name',
            'hotline',
            'description',
            'cycle_collect',
            'extension_time',
            'address',
        );
        $buildings = $project->buildings;
        foreach ($buildings as $b) {
            $floors = $b->floors;
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
                                    $inputs = $request->only(
                                        'name',
                                        'hotline',
                                        'description',
                                        'address',
                                    );
                                    $project->fill($inputs);
                                    $project->save();
                                    return $this->responseApi(
                                        new ProjectResource($project),
                                        200,
                                        'Sửa dữ liệu thành công'
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
        $project->fill($inputs);
        $project->save();

        return $this->responseApi(
            new ProjectResource($project),
            200,
            'Sửa dữ liệu thành công'
        );
    }

    public function delete(Project $project)
    {
        if (!$project) {
            return $this->responseApi([], 404, 'Đường dẫn không tồn tại');
        }
        $buildings = $project->buildings;
        foreach ($buildings as $b) {
            $floors = $b->floors;
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
                                    return $this->responseApi([], 403, 'Dự án đang sử dụng hợp đồng. Không thể xóa');
                                }
                            }
                        }
                    }
                }
            }
        }
        try {
            DB::beginTransaction();
            DB::enableQueryLog(); // Enable query log

            $project->buildings->each(function ($building) {
                $building->floors->each(function ($floor) {
                    $floor->rooms->each(function ($room) {
                        $room->service_index->each(function ($s) {
                            $s->delete();
                        });
                    });
                });
            });
            $project->buildings->each(function ($building) {
                $building->floors->each(function ($floor) {
                    $floor->rooms->each(function ($room) {
                        $room->beds->each(function ($bed) {
                            $bed->delete();
                        });
                    });
                });
            });
            $project->buildings->each(function ($building) {
                $building->floors->each(function ($floor) {
                    $floor->rooms->each(function ($room) {
                        $room->delete();
                    });
                });
            });
            $project->buildings->each(function ($building) {
                $building->floors->each(function ($floor) {
                    $floor->delete();
                });
            });
            $project->buildings->each(function ($building) {
                $building->delete();
            });
            $project->room_type->each(function ($rt) {
                $rt->delete();
            });
            $project->projectServices->each(function ($projectService) {
                $projectService->delete();
            });
            $project->delete();
            DB::enableQueryLog(); // Enable query log

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
