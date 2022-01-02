<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TypeAnnounce;
use App\Traits\TraitResponse;
use App\Http\Resources\TypeAnnounceResource;
use App\Http\Requests\TypeAnnouceStoreRequest;
use App\Http\Requests\TypeAnnouceUpdateRequest;

class TypeAnnoucementController extends Controller
{
    use TraitResponse;
    public function index(Request $request)
    {
        $TypeAnnounce = TypeAnnounce::with('announcements')->latest()->get();
        // if ($request->has('use_paginate')) {
        //     $TypeAnnounce = TypeAnnounce::with('announcements')->get();
        // } else {
        //     $TypeAnnounce = TypeAnnounce::simplePaginate(10);
        // }

        return TypeAnnounceResource::collection($TypeAnnounce);
    }

    public function show(TypeAnnounce $typeAnnounce)
    {
        if ($typeAnnounce != null) {
            return $this->responseApi(
                new TypeAnnounceResource($typeAnnounce),
                200,
                'Lấy dữ liệu thành công'
            );
        } else {

            return $this->responseApi(
                "",
                'Dữ liệu này không tồn tại ở bảng này.',
                400
            );
        }
    }

    public function store(TypeAnnouceStoreRequest $request)
    {
        $data = $request->except('_token');
        $result = TypeAnnounce::create($data);
        return $this->responseApi(
            new TypeAnnounceResource($result),
            200,
            'Thêm dữ liệu thành công'
        );
    }

    public function update(TypeAnnouceUpdateRequest $request, TypeAnnounce $typeAnnounce)
    {
        $inputs = $request->only(
            'name',
        );
        $typeAnnounce->fill($inputs);
        $typeAnnounce->save();
        return $this->responseApi(
            new TypeAnnounceResource($typeAnnounce),
            200,
            'Sửa dữ liệu thành công'
        );
    }

    public function delete(TypeAnnounce $typeAnnounce)
    {
        if (!$typeAnnounce) {
            return $this->responseApi("", 404, 'Đường dẫn không tồn tại');
        }

        $typeAnnounce->delete();
        return $this->responseApi($typeAnnounce, 200, 'Xóa Thành công');
    }
}
