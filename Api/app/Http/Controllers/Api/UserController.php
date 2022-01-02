<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\activeUserRequest;
use App\Http\Requests\StaffUpdateUserRequest;
use App\Http\Requests\studentUpdatefrom;
use App\Http\Resources\getActiveResource;
use App\Models\Roles;
use App\Models\StudentInfo;
use App\Models\StudentRelative;
use App\Models\User;
use App\Traits\TraitResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    use TraitResponse;
    public function getUserStudent(Request $request)
    {

        DB::enableQueryLog();
        $pagesize = 13;
        $User = User::where('user_type', config('User.userType.student'))->filter(request(['status', 'name', 'student_code']))->latest()->paginate($pagesize);
        Log::info(DB::getQueryLog());

        return $this->responseApi(['user' => $User, 'status' => config('User.action2')], 200);
    }

    public function getUserStaff(Request $request)
    {
        $pagesize = 13;
        $User = User::where('user_type', config('User.userType.Staff'))->filter(request(['status', 'name', 'role_name', 'role_id']))->latest()->paginate($pagesize);

        return $this->responseApi(['user' => $User, 'status' => config('User.action2')], 200);
    }

    public function getUserId(Request $request, $id)
    {
        $User = User::find($id);

        if (isset($User) && !empty($User)) {
            return $this->responseApi(['user' => $User, 'user_type' => config('User.userType2'), 'action' => config('User.action2')], 200);
        } else {
            return $this->responseApi("", 400, "Yêu cầu không chính xác");
        }
    }

    public function showRole()
    {
        return $this->responseApi(Roles::all()->paginate(10), 200);
    }

    public function adminEditUser(Request $request, $id)
    {
        $user = User::find($id);
        if (isset($user) && !empty($user)) {
            $data = [
                'users' => $user,
                'roles' => Roles::all(),
            ];
            return $this->responseApi($data, 200);
        } else {
            return $this->responseApi("", 400, "Yêu cầu không chính xác");
        }
    }

    public function adminUpdateUser(StaffUpdateUserRequest $request, $id)
    {
        if (baseGate('role-list') !== true) {
            return $this->responseApi("", 403, "Bạn không có quyền chức vụ");
        }

        if (auth()->user()->id == $id && $request->status == 0) {
            return $this->responseApi("", 400, "Người dùng không thể tự Vô hiệu hóa tài khoản");
        }
        $users = User::find($id);
        if (isset($users) && !empty($users) && $users->user_type == config('User.userType.Staff')) {
            try {
                DB::beginTransaction();
                $users->update($request->validated());
                DB::commit();
                return $this->responseApi("", 200, "Cập nhật thông tin thành công");
            } catch (Exception $exception) {
                DB::rollBack();
            }
        }
        return $this->responseApi("", 400, "Yêu cầu không chính xác");
    }
    public function studentUpdateUser(studentUpdatefrom $request, $requestId)
    {
        $users = User::find($requestId);

        if (isset($users) && !empty($users) && $users->user_type == config('User.userType.student')) {
            try {
                DB::beginTransaction();
                $users->update($request->only(['first_name', 'last_name', 'birth', 'birth_place', 'gender', 'address', 'phone', 'status']));

                $userInfo = StudentInfo::where('user_id', $requestId)->first();
                $userInfo->update($request->only(['student_code', 'department', 'student_year', 'nation', 'religion', 'CCCD', 'date_range', 'issued_by', 'student_object', 'school']));
                StudentRelative::where('student_info_id', $userInfo->id)->first()->update($request->only(['farther_name', 'mother_name', 'address_relative', 'phone_relative']));
                DB::commit();
                return $this->responseApi("", 200, "Cập nhật tài khoản thành công !");
            } catch (Exception $exception) {
                DB::rollBack();
            }
        }
        return $this->responseApi("", 500, 'Cập nhật tài khoản thất bại !');
    }

    public function userActive(activeUserRequest $request, $id)
    {

        $users = User::find($id);
        // dd($request->status);
        if (isset($users) && !empty($users)) {
            if ($request->status == 0) {
                if ($users->id == auth()->user()->id) {
                    return $this->responseApi("", 400, "Người dùng không thể tự Vô hiệu hóa tài khoản");
                }
                $users->update(['status' => config('User.action.disable')]);

                return $this->responseApi("", 200, "Vô hiệu hóa tài khoản người dùng thành công");
            }
            $users->update(['status' => config('User.action.activated')]);

            return $this->responseApi("", 200, "Kích hoạt tài khoản người dùng thành công");
        }

        return $this->responseApi("", 400, "Lỗi cú pháp trong yêu cầu");
    }
    public function getActive(Request $request, $id)
    {
        $users = User::find($id);
        if (isset($users) && !empty($users)) {
            return $this->responseApi(["users" => new getActiveResource($users), 'statusAll' => config('User.action2')], 200);
        }
        return $this->responseApi("", 400, "Lỗi cú pháp trong yêu cầu");
    }
}
