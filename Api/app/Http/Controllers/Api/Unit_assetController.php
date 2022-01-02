<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Unit_assetRequest;
use App\Models\Unit_asset;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;

class Unit_assetController extends Controller
{
    use TraitResponse;

    public function index(Request $request)
    {
        $this->middleware('can:unit-list');
        if(count($request->all()) == 0) {
            $models = Unit_asset::all();
        } else {
            $modelQuery = Unit_asset::where('name', 'like', "%" . $request->keyword . "%");
            $models = $modelQuery->get();
        }
        $models->load('assets');
        return $this->responseApi($models, 200);
    }

    public function updateOrCreate(Unit_assetRequest $request)
    {  
        
        if($request->route('unit_asset')) {
            $model = Unit_asset::find($request->route('unit_asset'));
            if (!$model) {
                return $this->responseApi("", 404, 'Đường dẫn không tồn tại');
            }
        }
        $model = Unit_asset::updateOrCreate(
            ['id' => $request->route('unit_asset')],
            [
                'name' => $request->name,
                'created_at' => $request->created_at,
                'updated_at' => $request->updated_at,
            ]
        );
        $model->save();
        return $model;
    }

    public function store(Unit_assetRequest $request)
    {
        $this->middleware('can:unit-add');
        $data = $this->updateOrCreate($request);
        return $this->responseApi($data, 200, 'Thêm thành công');
    }

    public function update(Unit_assetRequest $request)
    { 
        $this->middleware('can:unit-edit');
        $data = $this->updateOrCreate($request);

        return $this->responseApi($data, 200, 'Cập nhật thành công');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $this->middleware('can:unit-detail');
        $model = Unit_asset::find($id);

        if (!$model) {
            return $this->responseApi("", 404, 'Đường dẫn không tồn tại');
        }

        return $this->responseApi($model, 200);
    }
    public function destroy($id)
    {   
        $this->middleware('can:unit-delete');
        $model = Unit_asset::find($id);

        if (!$model) {
            return $this->responseApi("", 404, 'Đường dẫn không tồn tại');
        }

        $model->delete();
        return $this->responseApi($model, 200, 'Xóa Thành công');
    }
}
