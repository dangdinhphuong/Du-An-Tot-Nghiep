<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BedRequest;
use App\Models\Bed;
use App\Traits\TraitResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BedController extends Controller
{
    use TraitResponse;

    public function index(Request $request)
    {
        
        $models = Bed::filter($request->only(['room_id']))->with(['contract'])->get();
        return $this->responseApi($models, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateOrCreate(BedRequest $request)
    {

        if ($request->route('bed')) {
            $model = Bed::find($request->route('bed'));
            if (!$model) {
                return $this->responseApi([], 404, 'Đường dẫn không tồn tại');
            }
            $model->fill($request->all());

            $model->save();
        } else {
            $model = new Bed();
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
    public function store(BedRequest $request)
    {
        
        $data = $this->updateOrCreate($request);
        return $this->responseApi($data, 200, 'Thêm thành công');
    }

    public function update(BedRequest $request)
    {
        
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
        
        $model = Bed::find($id);

        if (!$model) {
            return $this->responseApi([], 404, 'Đường dẫn không tồn tại');
        }

        return $this->responseApi($model->load('room', 'contract'), 200);
    }

    public function destroy($id)
    {
        
        $model = Bed::find($id);

        if (!$model) {
            return $this->responseApi([], 404, 'Đường dẫn không tồn tại');
        }
        $contract = $model->contract;
        if ($contract != null) {
            $now = Carbon::now();
            $dayDiff = $now->diffInDays($contract->end_day);
            if ($contract == null || $dayDiff >= 0) {
                return $this->responseApi([], 403, 'Giường đang sử dụng hợp đồng. Không thể xóa');
            }
        }

        $model->delete();
        return $this->responseApi($model, 200, 'Xóa Thành công');
    }
}
