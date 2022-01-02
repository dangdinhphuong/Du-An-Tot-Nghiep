<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Traits\TraitResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\AnnouncementStroreRequest;
use App\Http\Requests\AnnouncementUpdateRequest;
use App\Http\Resources\AnnourcementResource;
// use App\Http\Resources\AnnoucementCollection;

class AnnouncementController extends Controller
{
    use TraitResponse;
    public function index(Request $request)
    {
        $Announcement = Announcement::filter($request->only(['title', 'type_announce_id', 'level', 'first_name']))->with(['typeAnnounce', 'user'])->orderBy('created_at', 'desc')->paginate(10);
        // if ($request->has('use_paginate')) {
        // } else {
        // $Announcement = Announcement::filter($request->only(['title', 'type_announce_id', 'level', 'first_name']))->with(['typeAnnounce', 'users'])->orderBy('created_at', 'desc')->paginate(10);
        // }
        // $Announcement['last_page'] = $Announcement->lastPage();
        // $Announcement['total'] = $Announcement->total();

        return AnnourcementResource::collection($Announcement);
    }

    public function show($announcement_id)
    {
        $announcement = Announcement::with(['typeAnnounce'])->find($announcement_id);
        if ($announcement != null) {
            return $this->responseApi(
                new AnnourcementResource($announcement),
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

    public function store(AnnouncementStroreRequest $request)
    {
        $data = $request->except('_token');
        $result = Announcement::create($data);
        return $this->responseApi(
            new AnnourcementResource($result),
            201,
            'Thêm dữ liệu thành công'
        );
    }

    public function update(AnnouncementUpdateRequest $request, Announcement $announcement)
    {

        $inputs = $request->only(
            'level',
            'title',
            'content',
            'user_id',
            'type_announce_id',
            'range',
            'date_start',
            'date_end',
        );

        $announcement->fill($inputs);
        $announcement->save();
        return $this->responseApi(
            new AnnourcementResource($announcement),
            200,
            'Sửa dữ liệu thành công'
        );
    }

    public function delete(Announcement $announcement)
    {
        if (!$announcement) {
            return $this->responseApi("", 404, 'Đường dẫn không tồn tại');
        }

        $announcement->delete();
        return $this->responseApi($announcement, 200, 'Xóa Thành công');
    }
}
