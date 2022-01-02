<?php

namespace App\Http\Controllers\Api\Staffs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\TraitResponse;
use App\Models\StudentInteract;
use App\Http\Requests\StudentInteractRequest;
use App\Http\Resources\Student_interactResources;
use App\Models\User;
use App\Mail\SendMail;
use Exception;

class Student_interactController extends Controller
{
    use TraitResponse;
    public function index(Request $request)
    {
        $studentInteract = StudentInteract::filter(request(['request_type', 'status', 'student_name']))->paginate(10);
        $studentInteract->load('staff');
        $studentInteract->load('student');
        return $this->responseApi([
            "studentInteract" => $studentInteract,
            "request_type" => config('User.StudentInter.requestType'),
            "status" => config('User.StudentInter.status')
        ], 200);
    }
    public function detail(Request $request, $id)
    {
        $studentInteract = StudentInteract::find($id);

        if (isset($studentInteract) && !empty($studentInteract)) {
            $studentInteract->load('staff');
            $studentInteract->load('student');
            return $this->responseApi([
                "studentInteract" => $studentInteract,
                "request_type" => config('User.StudentInter.requestType'),
                "status" => config('User.StudentInter.status')
            ], 200);
        } else {
            return $this->responseApi("Lỗi cú pháp trong yêu cầu", 400);
        }
    }
    public function create(Request $request)
    {
        $student = User::select('id', 'first_name', 'last_name')->where('user_type', config('User.userType.student'))->get();
        $staff  = User::select('id', 'first_name', 'last_name')->where('user_type', config('User.userType.Staff'))->get();
        return $this->responseApi([
            "staffs" => $staff,
            "student" => $student,
            "request_type" => config('User.StudentInter.requestType'),
            "status" => config('User.StudentInter.status')
        ], 200);
    }
    public function store(StudentInteractRequest $request)
    {

        $staff = User::find($request->staff_id); // kiểm tra có phải lại nhân viên ko

        if (isset($staff) && !empty($staff) && $staff->user_type === 1) {
            $student = User::find($request->student_id); // kiểm tra có phải lại sinh viên ko
            if (isset($student) && !empty($student) && $student->user_type === 0) {
                try {
                    DB::beginTransaction();
                    StudentInteract::create(array_merge(
                        $request->only(['request_type', 'content', 'status', 'student_id', 'staff_id']),
                        [
                            'date_send' => date('Y-m-d H:i:s'),
                            'check' => 0,
                        ]
                    ));
                    DB::commit();
                    return $this->responseApi("", 200, "Thêm tương tác thành công !");
                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::info('Error :');
                    Log::info($exception);
                    return $this->responseApi($exception, 400, 'Thêm tương tác thất bại !');
                }
            }
            return $this->responseApi('', 400, 'Người dùng không phải sinh viên');

        }
        return $this->responseApi('', 400, 'Thêm tương tác thất bại !');
    }

    public function edit(Request $request, $id)
    {

        $studentInteract = StudentInteract::find($id);

        if (isset($studentInteract) && !empty($studentInteract)) {
           
            $studentInteract->load('student','staff');
           // dd($studentInteract);
            return $this->responseApi([
                'studentInteract'=>new Student_interactResources($studentInteract) ,
                // "staffs" => $staff,   'studentInteract'=>new Student_interactResources($studentInteract) ,staff
                // "student" => $student,
                "request_type" => config('User.StudentInter.requestType'),
                "status" => config('User.StudentInter.status')
            ], 200);
        } else {
            return $this->responseApi("", 400, "Lỗi cú pháp trong yêu cầu");
        }
    }

    public function update(StudentInteractRequest $request, $id)
    {
        $studentInteract = StudentInteract::find($id);
        if (isset($studentInteract) && !empty($studentInteract)) {
            $staff = User::find($request->staff_id); // kiểm tra có phải lại nhân viên ko
            if (isset($staff) && !empty($staff) && $staff->user_type === 1) {
                $student = User::find($request->student_id); // kiểm tra có phải lại sinh viên ko
                if (isset($student) && !empty($student) && $student->user_type === 0) {
                    try {
                        DB::beginTransaction();
                        $studentInteract->update(array_merge(
                            $request->only(['request_type', 'content', 'status', 'student_id', 'staff_id', 'check'])
                        ));
                        DB::commit();
                        return $this->responseApi("", 200, "Sửa tương tác thành công !");
                    } catch (Exception $exception) {
                        DB::rollBack();
                    }
                }
            }
        }
        return $this->responseApi("", 400, 'Sửa tương tác thất bại !');
    }

    public function delete(Request $request, $id)
    {
        $studentInteract = StudentInteract::find($id);

        if (isset($studentInteract) && !empty($studentInteract)) {
            $studentInteract->delete();
            return $this->responseApi("", 200, "Xóa tương tác thành công !");
        } else {
            return $this->responseApi("", 400, "Xóa tương tác thành công !");
        }
    }
}
