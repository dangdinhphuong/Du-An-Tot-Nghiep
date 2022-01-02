<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProducerRequest;
use App\Models\Producer;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;

class ProducerController extends Controller
{
    use TraitResponse;

    public function index(Request $request)
    {   
        $this->middleware('can:producer-list');
        if(count($request->all()) == 0) {
            $models = Producer::all();
        } else {
            $modelQuery = Producer::where('name', 'like', "%" . $request->keyword . "%");
            $models = $modelQuery->get();
        }
        $models->load('assets');
        return $this->responseApi($models, 200);
    }

    public function updateOrCreate(ProducerRequest $request)
    {
        if($request->route('producer')) {
            $model = Producer::find($request->route('producer'));
            if (!$model) {
                return $this->responseApi("", 404, 'Đường dẫn không tồn tại');
            }
        }
        $model = Producer::updateOrCreate(
            ['id' => $request->route('producer')],
            [
                'name' => $request->name,
                'created_at' => $request->created_at,
                'updated_at' => $request->updated_at,
            ]
        );
        $model->save();
        return $model;
    }

    public function store(ProducerRequest $request)
    {
        $this->middleware('can:producer-add');
        $data = $this->updateOrCreate($request);

        return $this->responseApi($data, 200, 'Thêm thành công');
    }

    public function update(ProducerRequest $request)
    {
        $this->middleware('can:producer-edit');
        $data = $this->updateOrCreate($request);

        return $this->responseApi($data, 200, 'Cập nhật thành công');
    }

    public function show($id)
    {
        $this->middleware('can:producer-detail');
        $model = Producer::find($id);
        if (!$model) {
            return $this->responseApi("", 404, 'Đường dẫn không tồn tại');
        }

        return $this->responseApi($model, 200);
    }
    public function destroy($id)
    {
        $this->middleware('can:producer-delete');
        $model = Producer::find($id);

        if (!$model) {
            return $this->responseApi("", 404, 'Đường dẫn không tồn tại');
        }

        $model->delete();
        return $this->responseApi($model, 200, 'Xóa Thành công');
    }
}
