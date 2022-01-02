<?php

namespace App\Http\Controllers\Api\Students;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMultipleForm;
use App\Models\StudentInfo;
use App\Models\StudentRelative;
use App\Models\User;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use TraitResponse;
    public function __construct()
    {
        $this->middleware('auth.jwt', ['except' => ['studentRegister']]);
    }
    public function studentRegister(StoreMultipleForm $request)
    {
        try {
            DB::beginTransaction();
            $user =   User::create(array_merge(
                $request->except(['password', 'remember_token','role_id', 'status', 'student_code', 'department', 'student_year', 'nation', 'religion', 'CCCD', 'date_range', 'issued_by', 'student_object', 'school', 'fathername', 'mothername', 'address_relative', 'phone_relative']),
                [
                    'password' => bcrypt($request->password),
                    "role_id" => 2, // role cua sinh vien
                    "status" => config('User.action.disable') //không kích hoạt tài khoản

                ]
            ));
            $studentInfo = new StudentInfo($request->only(['student_code', 'department', 'student_year', 'nation', 'religion', 'CCCD', 'date_range', 'issued_by', 'student_object', 'school']));
            $studentInf = $user->studentInfo()->save($studentInfo);
            $studentRelativeArr = [
                'farther_name' =>  $request->fathername,
                'mother_name' => $request->mothername,
                'address_relative' => $request->address_relative,
                'phone_relative' => $request->phone_relative
            ];
            $studentRelativeObj = new StudentRelative($studentRelativeArr);
            $studentInf->studentRelative()->save($studentRelativeObj);
            DB::commit();
            return $this->responseApi("", 200, "Đăng ký tài khoản thành công !");
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->responseApi("", 500, 'Đăng ký tài khoản thất bại !');
        }
        return $this->responseApi("", 200, "Đăng ký tài khoản thành công !");
    }

    public function getStudentSearch(Request $request)
    {

        $Users = DB::table('users')->where('user_type', 0);
        $Users->where('first_name', 'LIKE', '%' . $request->name . '%');
        $Users->Where('last_name', 'LIKE', '%' . $request->name . '%');
        $Users = $Users->select('id', 'first_name', 'last_name')->get();

        if (isset($Users) && !empty($Users)) {
            return $this->responseApi($Users, 200,'Lấy dữ liệu thành công');
        }
        return $this->responseApi("", 404, "Không tìm thấy ");
    }
}
