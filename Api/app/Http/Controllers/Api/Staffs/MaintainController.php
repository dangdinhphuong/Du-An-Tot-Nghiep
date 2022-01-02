<?php

namespace App\Http\Controllers\Api\Staffs;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaintainCreateRequest;
use App\Http\Requests\MaintainUpdateRequest;
use App\Http\Requests\MaintainUpdateStatusRequest;
use App\Mail\MaintainEmail;
use App\Mail\UnderTakeEmail;
use App\Models\Maintenance;
use App\Models\User;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MaintainController extends Controller
{
    use TraitResponse;
    public function __construct()
    {
        $this->middleware('auth.jwt');
    }
    public function index()
    {
        return $this->responseApi(Maintenance::with(
            [
                'userCreate' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                },
                'userUndertake' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                },
            ]
        )->filter(request(['name', 'user_undertake_id', 'type', 'status']))->paginate(5), 200, 'Lấy dữ liệu thành công');
    }
    public function create()
    {
        $data = [];
        $data['periodic'] = config('maintain.periodic');
        $data['type'] = ['SCPS', 'KHDK'];
        $data['users'] = [];
        User::chunk(200, function ($main) use (&$data) {
            foreach ($main as $m) {
                if ($m->user_type == config('User.userType.Staff')) {
                    $data['users'][] = $m;
                }
            }
        });
        return $this->responseApi($data, 200, 'Lấy dữ liệu thành công');
    }
    public function store(MaintainCreateRequest $request)
    {
        Maintenance::create(array_merge(
            $request->only(['name', 'type', 'note', 'user_undertake_id', 'date_start', 'date_end', 'periodic']),
            [
                'user_create_id' => auth()->user()->id,
                'status' => config('maintain.status.Cho_thuc_hien')
            ]
        ));
        return $this->responseApi('', 201, "Tạo công việc bảo trì sửa chữa thành công !");
    }
    public function edit(Request $request, $id)
    {
        $data['maintain'] = Maintenance::where('id', $id)->get(['name', 'note', 'user_undertake_id']);

        if (!isset($data['maintain']) && empty($data['maintain'])) {
            return $this->responseApi('', 404, 'Đường dẫn không tồn tại');
        }
        $data['user'] = [];
        User::chunk(200, function ($main) use (&$data) {
            foreach ($main as $m) {
                if ($m->user_type == config('User.userType.Staff')) {
                    $data['users'][] = $m;
                }
            }
        });
        return $this->responseApi($data, 200, 'Lấy dữ liệu thành công');
    }
    public function update(MaintainUpdateRequest $request, $id)
    {
        $maintain = Maintenance::find($id);
        if (!$maintain) {
            return $this->responseApi("", 404, "Không tìm thấy bản ghi");
        }
        $maintain->update($request->all());
        return $this->responseApi($maintain, 201, 'Cập nhật thành công');
    }
    public function updateStatus(MaintainUpdateStatusRequest $request, $id)
    {
        $maintain = Maintenance::find($id);
        if (!$maintain) {
            return $this->responseApi("", 404, "Không tìm thấy bản ghi");
        }
        $maintain->update($request->only('status'));
        return $this->responseApi($maintain, 201, 'Cập nhật thành công');
    }
    public function destroy($id)
    {
        $maintain = Maintenance::find($id);
        if (!$maintain) {
            return $this->responseApi("", 404, "Không tìm thấy bản ghi");
        }
        $maintain->delete();
        return $this->responseApi("", 200, 'Xóa thành công');
    }
    public function sendMail(Request $request)
    {
        $userCreate = Maintenance::with('userCreate')->whereNotNull('periodic')->get();
        $userUnderTake = Maintenance::with('userUndertake')->whereNotNull('periodic')->get();
        $userArry['userCreate'] = [];
        $userArry['userUnderTake'] = [];
        foreach ($userCreate as $user) {
            array_push($userArry['userCreate'], $user->toArray());
        }
        foreach ($userUnderTake as $user) {
            array_push($userArry['userUnderTake'], $user->toArray());
        }
        foreach ($userArry['userCreate'] as $user) {
            try {
                Mail::to($user['user_create']['email'])->send(new MaintainEmail($user));
                echo 'Mail send successfully';
            } catch (\Exception $e) {
                echo 'Error - ' . $e;
            }
        }
        foreach ($userArry['userUnderTake'] as $user) {
            try {
                Mail::to($user['user_undertake']['email'])->send(new UnderTakeEmail($user));
                echo 'Mail send successfully';
            } catch (\Exception $e) {
                echo 'Error - ' . $e;
            }
        }
    }
}
