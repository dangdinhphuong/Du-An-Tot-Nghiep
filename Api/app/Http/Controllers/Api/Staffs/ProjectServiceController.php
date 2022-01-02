<?php

namespace App\Http\Controllers\Api\Staffs;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectServiceRequest;
use App\Http\Requests\ProjectServiceUpdateRequest;
use App\Models\ProjectService;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;

class ProjectServiceController extends Controller
{
    //
    use TraitResponse;
    public function __construct()
    {
        $this->middleware('auth.jwt');
    }
    public function index(Request $request)
    {
        return $this->responseApi(ProjectService::filter($request->only(['project_id']))->get(), 200, 'Lấy dữ liệu thành công');
    }
    public function create()
    {
        $data['electric'] = config('service_unit.electric');
        $data['water'] = config('service_unit.water');
        $data['other'] = config('service_unit.other');
        return $this->responseApi($data, 200, 'Lấy dữ liệu thành công');
    }
    public function store(ProjectServiceRequest $request)
    {
        // dd($request->all());
        foreach ($request->all() as $projectservice) {
            ProjectService::create($projectservice);
        }
        return $this->responseApi("", 201, "Tạo bảng giá dịch vụ thành công !");
    }
    public function edit(Request $request, $id)
    {
        $data['project_service'] = ProjectService::where('project_id', $id)->get(['id', 'name', 'unit_price', 'unit', 'project_id']);
        if (!isset($data['project_service']) || empty($data['project_service'])) {
            return $this->responseApi(['Đường dẫn không tồn tại'], 404);
        }
        return $this->responseApi($data, 200, 'Lấy dữ liệu thành công');
    }
    public function update(ProjectServiceUpdateRequest $request)
    {
        foreach ($request->all() as $projectservice) {
            $service = ProjectService::find($projectservice['id']);

            if ($service == null) {
                return $this->responseApi("", 404, "Không tìm thấy bản ghi");
            }
            $buildings =  $service->project->buildings;
            foreach ($buildings as $b) {
                $floors = $b->floors;
                foreach ($floors as $f) {
                    $rooms = $f->rooms;
                    foreach ($rooms as $r) {
                        $contracts = $r->contracts;
                        if ($contracts != null) {
                            foreach ($contracts as $c) {
                                if ($c != null) {
                                    $projectServices = ProjectService::find(json_decode($c->project_service_id));
                                    foreach ($projectServices as $ps) {
                                        if ($ps->id  == $projectservice['id']) {
                                            return $this->responseApi("", 403, "Hợp đồng đang sử dụng dịch vụ dự án không thể cập nhật");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            ProjectService::where('id', $projectservice['id'])->where('project_id', $projectservice['project_id'])->update($projectservice);
        }
        return $this->responseApi('', 201, 'Cập nhật thành công');
    }
    public function delete($pid)
    {
        // $psid,
        // $maintain = ProjectService::where('project_id', $pid)->where('id', $psid);
        $projectservice = ProjectService::find($pid);

        if ($projectservice == null) {
            return $this->responseApi("", 404, "Không tìm thấy bản ghi");
        }
        $buildings =  $projectservice->project->buildings;
        foreach ($buildings as $b) {
            $floors = $b->floors;
            foreach ($floors as $f) {
                $rooms = $f->rooms;
                foreach ($rooms as $r) {
                    $contracts = $r->contracts;
                    foreach ($contracts as $c) {
                        if ($c != null) {
                            $projectService = ProjectService::find(json_decode($c->project_service_id));
                            foreach ($projectService as $ps) {
                                if ($ps->id  == $pid) {
                                    return $this->responseApi("", 403, "Hợp đồng đang sử dụng dịch vụ dự án không thể xóa");
                                }
                            }
                        }
                    }
                }
            }
        }
        // dd($contracts);
        $projectservice->delete();
        return $this->responseApi("", 201, 'Xóa thành công');
    }
}
