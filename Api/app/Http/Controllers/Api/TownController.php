<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Town;
use App\Traits\TraitResponse;
use App\Http\Resources\TownResource;
class TownController extends Controller
{
    use TraitResponse;

    public function index()
    {
        $towns = Town::with(['districts', 'wards'])->all();
        return $this->responseApi(
            TownResource::collection($towns),
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }
}
