<?php

namespace App\Http\Controllers\Api\Staffs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Traits\TraitResponse;
use App\Http\Requests\Task_StoreRequest;
use App\Http\Requests\filterRequest;
use App\Http\Requests\getStatusRequest;
use App\Models\User;
use App\Models\Tasks;
use Exception;

class TaskController extends Controller
{
    use TraitResponse;
    public function index(filterRequest $request)
    {
     
        //$tasks = Tasks::select('id', 'title', 'date_start', 'date_end', 'priority', 'desc', 'user_create_id', 'user_undertake_id', 'status', 'processed')->paginate(10);
        $tasks = Tasks::filter(request(['title','date_start', 'date_end','priority']))->paginate(10);
        
        $tasks->load('user_create');
        $tasks->load('user_undertake');
        return $this->responseApi(["tasks" => $tasks, "priority_list" => config('priority'), "status" => config('User.status')], 200);
    }

    public function detail(Request $request, $id)
    {
        $tasks = Tasks::select('id', 'title', 'date_start', 'date_end', 'priority', 'desc', 'user_create_id', 'user_undertake_id', 'status', 'processed')->find($id);
        if (isset($tasks) && !empty($tasks)) {
            $tasks->load('user_create');
            $tasks->load('user_undertake');
            return $this->responseApi(["tasks" => $tasks, "priority_list" => config('priority'), "status" => config('User.status')], 200);
        } else {
            return $this->responseApi("Lỗi cú pháp trong yêu cầu", 400);
        }
    }
    public function create(Request $request)
    {
        $users = User::select('id', 'first_name', 'last_name')->where('user_type', config('User.userType.Staff'))->get();
        return $this->responseApi(["staffs" => $users, "priority_list" => config('priority'), "status" => config('User.status1')], 200);
    }
    public function store(Task_StoreRequest $request)
    {

        $auth_id = auth()->user()->id;
        $users = User::where('id',  $request->user_undertake_id)->first(); // kiểm tra có phải lại nhân viên ko
        if (isset($users) && !empty($users) && $users->user_type == 1) {
            try {
                DB::beginTransaction();
                $tasks =   Tasks::create(array_merge(
                    $request->only(['title',  'status' ,'date_start', 'date_end', 'priority', 'desc', 'user_undertake_id']),
                    [
                        'user_create_id' => $auth_id
                    ]
                ));
                DB::commit();
                return $this->responseApi("", 200, "Thêm task thành công !");
            } catch (Exception $exception) {
                DB::rollBack();
            }
        }

        return $this->responseApi("", 400, 'Thêm task thất bại !');
    }

    public function edit(Request $request, $id)
    {

        $tasks = Tasks::find($id);

        if (isset($tasks) && !empty($tasks)) {
            $users = User::select('id', 'first_name', 'last_name')->where('user_type', config('User.userType.Staff'))->get();
            return $this->responseApi(['task' => $tasks, "staffs" => $users, "priority_list" => config('priority')], 200);
        } else {
            return $this->responseApi("", 400, "Lỗi cú pháp trong yêu cầu");
        }
    }

    public function update(Task_StoreRequest $request, $id)
    {

        $users = User::where('id',  $request->user_undertake_id)->first(); // kiểm tra có phải lại nhân viên ko
        if (isset($users) && !empty($users) && $users->user_type == 1) {
            $task=Tasks::find($id);
            if($task){
                try {
                    DB::beginTransaction();
                    Tasks::find($id)->update($request->except(['user_create_id']));
                    DB::commit();
    
                    return $this->responseApi("", 200, "Sửa task thành công !");
                } catch (Exception $exception) {
                    DB::rollBack();
                }
            }
            return $this->responseApi("", 400, 'Task cần sửa không tồn tại !');
        }
        return $this->responseApi("", 400, 'Sửa task thất bại !');
    }

    public function delete(Request $request, $id)
    {
        $tasks = Tasks::find($id);
        if (isset($tasks) && !empty($tasks)) {
            $tasks->delete();
            return $this->responseApi("", 200, "Xóa task thành công !");
        } else {
            return $this->responseApi("", 400, "Lỗi cú pháp trong yêu cầu");
        }
    }
    public function getStatus(getStatusRequest $request, $id)
    {
        $task=Tasks::find($id);
        if($task){
            try {
                DB::beginTransaction();
                $task->update($request->only(['status']));
                DB::commit();

                return $this->responseApi($task, 200, "Sửa task thành công !");
            } catch (Exception $exception) {
                DB::rollBack();
            }
        }
        return $this->responseApi("", 400, 'Task cần sửa không tồn tại !');
    }

}