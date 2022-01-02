<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssetRequest;
use App\Models\Asset;
use App\Models\Asset_type;
use App\Models\Producer;
use App\Models\Unit_asset;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    use TraitResponse;

    public function index(Request $request)
    {   
         $this->middleware('can:asset-list');
        $pagesize = 10;
        if(count($request->all()) == 0) {
            $models = Asset::paginate($pagesize);
        } else {
            $modelQuery = Asset::where('name', 'like', "%" . $request->keyword . "%");
            if ($request->has('producer_id') && $request->producer_id != "") {
                $modelQuery = $modelQuery->where('producer_id', $request->producer_id);
            }
            if ($request->has('unit_asset_id') && $request->unit_asset_id != "") {
                $modelQuery = $modelQuery->where('unit_asset_id', $request->unit_asset_id);
            }
            if ($request->has('asset_type_id') && $request->asset_type_id != "") {
                $modelQuery = $modelQuery->where('asset_type_id', $request->asset_type_id);
            }
            $models = $modelQuery->latest()->paginate($pagesize);
        }

        $models->load('producer', 'unit_asset', 'type_asset');
        return $this->responseApi($models, 200);
    }


    public function getDataToAsset() {
        $data = [
            'unit_assets' => Unit_asset::select('id', 'name')->get(),
            'asset_types' => Asset_type::select('id', 'name')->get(),
            'producers' => Producer::select('id', 'name')->get(),
        ];
        return $this->responseApi($data, 200, 'Success');
    }

    public function updateOrCreate(AssetRequest $request)
    {

        if ($request->route('asset')) {
            $model = Asset::find($request->route('asset'));
            if (!$model) {
                return $this->responseApi("", 404, 'Đường dẫn không tồn tại');
            }
            $model->fill($request->all());
            $model->unit_asset_id = $request->unit_asset_id;
            $model->producer_id = $request->producer_id;
            $model->asset_type_id = $request->asset_type_id;
            $model->image = $request->image ? $request->image : 'No image';
            $model->save();
        } else {
            $model = new Asset();
            $model->fill($request->all());
            $model->unit_asset_id = $request->unit_asset_id;
            $model->producer_id = $request->producer_id;
            $model->asset_type_id = $request->asset_type_id;
            $model->image = $request->image ? $request->image : 'No image';
            $model->save();
        }
        return $model;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssetRequest $request)
    {
        $this->middleware('can:asset-add');
        $data = $this->updateOrCreate($request);
        return $this->responseApi($data, 200, 'Thêm thành công');
    }
    public function update(AssetRequest $request)
    {
        $this->middleware('can:asset-edit');
        $data = $this->updateOrCreate($request);
        return $this->responseApi($data, 200, 'Cập nhật thành công');
    }

    public function show($id)
    {
        $this->middleware('can:asset-detail');
        $model = Asset::find($id);

        if (!$model) {
            return $this->responseApi('', 404, 'Đường dẫn không tồn tại');
        }

        return $this->responseApi($model, 200);
    }
    public function destroy($id)
    {
        $this->middleware('can:asset-delete');
        $model = Asset::find($id);

        if (!$model) {
            return $this->responseApi('', 404, 'Đường dẫn không tồn tại');
        }

        $model->delete();
        return $this->responseApi($model, 200, 'Xóa Thành công');
    }

    public function scopeFilter($query, array $filters)
    {
        // $query->when($filters['name','asset_type_id','asset_type_id'] ?? false, function ($query, $id) {
        $query->when($filters['name'] ?? false, function ($query, $name) {
            $query->where('name','like','%'.$name.'%');
            // ->orWhere('name','like','%'.$filters['name'].'%');
        });
    //prefix data
    }
    public function prefixData(Request $request)
    {
        $data=[];
        $data['producer']=Producer::all();
        $data['unit_asset']=Unit_asset::all();
        $data['asset_type']=Asset_type::all();
        return $this->responseApi($data, 200);
    }
}
