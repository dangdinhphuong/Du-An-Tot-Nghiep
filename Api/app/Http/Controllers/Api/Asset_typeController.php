<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Asset_typeRequest;
use App\Models\Asset_type;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;

class Asset_typeController extends Controller
{
    use TraitResponse;

    public function index(Request $request)
    {
        $this->middleware('can:type-asset-list');
        if(count($request->all()) == 0) {
            $models = Asset_type::all();
        } else {
            $modelQuery = Asset_type::where('name', 'like', "%" . $request->keyword . "%");
            $models = $modelQuery->get();
        }
        $models->load('assets');
        return $this->responseApi($models, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateOrCreate(Asset_typeRequest $request)
    {
       
        if($request->route('type_asset')) {
            $model = Asset_type::find($request->route('type_asset'));
            if (!$model) {
                return $this->responseApi("", 404, 'Đường dẫn không tồn tại');
            }
        }
        $model = Asset_type::updateOrCreate(
            ['id' => $request->route('type_asset')],
            [
                'name' => $request->name,
                'created_at' => $request->created_at,
                'updated_at' => $request->updated_at,
            ]
        );
        $model->save();
        return $model;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Asset_typeRequest $request)
    {
        $this->middleware('can:type-asset-add');
        $data = $this->updateOrCreate($request);
        return $this->responseApi($data, 200, 'Thêm thành công');
    }

    public function update(Asset_typeRequest $request)
    {
        $this->middleware('can:type-asset-edit');
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
        $this->middleware('can:type-asset-detail');
        $model = Asset_type::find($id);

        if (!$model) {
            return $this->responseApi('', 404, 'Đường dẫn không tồn tại');
        }

        return $this->responseApi($model, 200);
    }
    public function destroy($id)
    {
        $this->middleware('can:type-asset-delete');
        $model = Asset_type::find($id);

        if (!$model) {
            return $this->responseApi('', 404, 'Đường dẫn không tồn tại');
        }

        $model->delete();
        return $this->responseApi($model, 200, 'Xóa Thành công');
    }
}
