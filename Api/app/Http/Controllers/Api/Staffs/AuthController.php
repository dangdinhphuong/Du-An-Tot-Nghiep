<?php

namespace App\Http\Controllers\Api\Staffs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffRegisterRequest;
use App\Models\User;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;
use App\Models\StudentInfo;
use App\Models\StudentRelative;
use App\Http\Requests\StoreMultipleForm;
use Exception;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use TraitResponse;
    public function __construct()
    {
        $this->middleware('auth.jwt');
    }
    public function staffRegister(StaffRegisterRequest $request)
    {

        User::create(array_merge(
            $request->except(['password', 'remember_token', 'student_code', 'department', 'student_year', 'nation', 'religion', 'CCCD', 'date_range', 'issued_by', 'student_object', 'school']),
            [
                'password' => bcrypt($request->password),
                "user_type" => config('User.userType.Staff'), // loại tài khoản nhân viên
            ]
        ));
        return $this->responseApi('', 200, "Đăng ký tài khoản thành công !");
    }
    public function studentRegister(StoreMultipleForm $request) // nhân viên đăng ký tài khoản cho sinh viên
    {

        try {
            DB::beginTransaction();
            $user =   User::create(array_merge(
                $request->except(['password', 'remember_token', 'role_id', 'student_code', 'department', 'student_year', 'nation', 'religion', 'CCCD', 'date_range', 'issued_by', 'student_object', 'school', 'fathername', 'mothername', 'address_relative', 'phone_relative']),
                [
                    'password' => bcrypt($request->password),
                    "role_id" => 1, // role cua sinh vien
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
}
