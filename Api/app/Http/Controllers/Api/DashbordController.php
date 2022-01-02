<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Room;
use App\Models\Bed;
use App\Models\Contract;
use App\Models\User;
use App\Models\StudentInfo;
use Carbon\Carbon;
use App\Traits\TraitResponse;
use Illuminate\Support\Facades\DB;

class DashbordController extends Controller
{
    use TraitResponse;
    public function index()
    {
        // count thống kê số lượng
        $countProjects = Project::count();
        $countBuildings = Building::count();
        $countFloors = Floor::count();
        $countBeds = Bed::count();
        $countRooms = Room::all()->count();
        $countStudents = User::where('user_type', config('User.userType.student'))->count();

        // $listContract = Contract::whereDate('end_day', '>=', now())->get();
        // $countBedContactExist = Bed::whereHas('contract', function ($query) {
        //     $query->whereDate('end_day', '>=', now());
        // })->count();
        // $countBedsNotContract = Bed::doesntHave('contract')->count();

        // $countBedContactExist = Bed::whereHas('contract')->get()->count();
        // $a = Bed::doesntHave('contract')->get()->count();
        // tính số hợp đồng có giường là:
        // tính số giường chưa có hợp đồng
        // $countBedsNotContract = Bed::doesntHave('contract')->orWhereHas('contract', function($query) {
        //     $query->where('end_day', '>=', now());\
        // })->get()->count();

        // dd($countBedsNotContract);
        // dd($countBedsNotContract);
        // dd($a1);
        // thống kê dữ liệu thu tiền theo tháng

        // $countRoomContactExist = Room::whereHas('contracts', function ($query) {
        //     $query->whereDate('end_day', '>=', now());
        // })->count();
        // $countRoomsNotContract = Room::doesntHave('contracts')->count();

        return $this->responseApi(
            [
                'count_projects' => $countProjects,
                'count_buildings' => $countBuildings,
                'count_floors' => $countFloors,
                'count_beds' => $countBeds,
                'count_students' => $countStudents,
                'count_rooms' => $countRooms,
            ],
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }

    public function totalBed(Request $request)
    {
        $listContract = Contract::whereDate('end_day', '>=', now())->get();
        $countBedContactExist = Bed::whereHas('contract', function ($query) {
            $query->whereDate('end_day', '>=', now());
        })->count();
        $countBedsNotContract = Bed::doesntHave('contract')->count();
        $getRoomContactExist = Room::whereHas('contracts', function ($query) {
            $query->whereDate('end_day', '>=', now());
        })->get();
        $countRoomsNotContract = Room::doesntHave('contracts')->get();
        $listRoomsNotContract = Room::doesntHave('contracts')->get();

        return $this->responseApi(
            [
                'list_bed_contract' => $listContract,
                'count_bed_contract' => $countBedContactExist,
                'count_bed_not_contract' => $countBedsNotContract,
                'count_room' => $getRoomContactExist->count(),
                'count_room_not_contract' => $listRoomsNotContract->count(),
                'list_bed_not_contract' => $countRoomsNotContract,
                'list_room_contract' => $getRoomContactExist,
                'list_room_not_contract' => $listRoomsNotContract,
            ],
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }

    public function monthUser()
    {
        foreach (config('Months.months') as $month) {

            $modInsertDate = '1' . '-' . $month . '-' . Carbon::now()->format('y');
            $lastDayOfMonth = Carbon::parse($modInsertDate)->endOfMonth()->format('Y-m-d');
            $date = date('Y-m-d', strtotime($modInsertDate));
            $user = User::where('user_type', config('User.userType.student'))->where('status', config('User.action.activated'))->whereBetween('created_at', [$date, $lastDayOfMonth])->count();

            $data[] = ['name' => $modInsertDate, 'value' => $user];
        }
        return $this->responseApi(
            $data,
            200,
            'Lấy danh sách dữ liệu thành công'
        );
    }
}
