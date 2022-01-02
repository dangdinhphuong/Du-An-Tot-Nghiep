<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room_typeRequest;
use App\Models\Room_type;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;

class Room_typeController extends Controller
{
    use TraitResponse;

    public function index(Request $request)
    { 
        $this->middleware('can:type-room-list');
        $models = Room_type::filter($request->only(['project_id']))->get();
        return $this->responseApi($models, 200);
    }

    public function updateOrCreate(Room_typeRequest $request)
    {

        if ($request->route('room_type')) {
            $model = Room_type::find($request->route('room_type'));
            if (!$model) {
                return $this->responseApi([], 404, 'Đường dẫn không tồn tại');
            }
            $model->fill($request->all());

            $model->save();
        } else {
            $model = new Room_type();
            $model->fill($request->all());

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
    public function store(Room_typeRequest $request)
    {
        $this->middleware('can:type-room-add');
        $data = $this->updateOrCreate($request);
        return $this->responseApi($data, 200, 'Thêm thành công');
    }

    public function update(Room_typeRequest $request)
    {
        $this->middleware('can:type-room-edit');
        $data = $this->updateOrCreate($request);

        return $this->responseApi($data, 200, 'Cập nhật thành công');
    }

    public function show($id)
    {
        $this->middleware('can:type-room-detail');
        $model = Room_type::find($id);

        if (!$model) {
            return $this->responseApi([], 404, 'Đường dẫn không tồn tại');
        }

        return $this->responseApi($model, 200);
    }

    public function destroy($id)
    {
        $this->middleware('can:type-room-delete');
        $model = Room_type::find($id);

        if (!$model) {
            return $this->responseApi([], 404, 'Đường dẫn không tồn tại');
        }

        $model->delete();
        return $this->responseApi($model, 200, 'Xóa Thành công');
    }
    
}
