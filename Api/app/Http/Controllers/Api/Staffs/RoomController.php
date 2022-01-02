<?php

namespace App\Http\Controllers\Api\Staffs;

use App\Http\Controllers\Controller;
use App\Jobs\CreateServiceIndexRecord;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    //
    public function testService(Request $request)
    {
        $id = Room::create($request->all())->id;
        CreateServiceIndexRecord::dispatch($id);
        return 'success';
    }
}
