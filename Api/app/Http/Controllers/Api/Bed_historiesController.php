<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bed_historiesRequest;
use App\Models\Bed_History;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;

class Bed_historiesController extends Controller
{
    use TraitResponse;

    public function index()
    {
        $models = Bed_History::with(['room', 'bed'])->paginate(10);
        return $this->responseApi($models, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateOrCreate(Bed_historiesRequest $request)
    {   

        if($request->route('bed_history')) {
            $model = Bed_History::find($request->route('bed_history'));
            if(!$model) {
                return $this->responseApi([], 404, 'Đường dẫn không tồn tại');
            }
            $model->fill($request->all());
            
            $model->save();
        } else {
            $model = new Bed_History();
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
    public function store(Bed_historiesRequest $request) {
        $data = $this->updateOrCreate($request);
        return $this->responseApi($data, 200, 'Thêm thành công');
    }

    public function update(Bed_historiesRequest $request) {
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
        $model = Bed_History::find($id);

        if(!$model) {
            return $this->responseApi([], 404, 'Đường dẫn không tồn tại');
        }

        return $this->responseApi($model, 200);
    }
    
    public function destroy($id)
    {
        $model = Bed_History::find($id);

        if(!$model) {
            return $this->responseApi([], 404, 'Đường dẫn không tồn tại');
        }

        $model->delete();
        return $this->responseApi($model, 200, 'Xóa Thành công');
    }
}
