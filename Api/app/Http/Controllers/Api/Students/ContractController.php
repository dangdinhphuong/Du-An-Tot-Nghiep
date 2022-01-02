<?php

namespace App\Http\Controllers\Api\Students;

use App\Http\Controllers\Controller;
use App\Http\Resources\BedContractResource;
use App\Models\Bed;
use App\Models\Contract;
use App\Models\ProjectService;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    use TraitResponse;
    public function index(Request $request)
    {

        $contract = Contract::where('user_id', auth()->user()->id)->with(['user', 'bed', 'room', 'room.room_type'])->first();
        if ($contract == null) {
            return $this->responseApi([], 400, 'Không tìm thấy dữ liệu');
        }
        $projectService = ProjectService::find(json_decode($contract->project_service_id));
        $contract->project_service  = $projectService;

        if (!is_null($contract)) {
            return $this->responseApi($contract, 200, 'Lấy dữ liệu thành công');
        }
    }
}
