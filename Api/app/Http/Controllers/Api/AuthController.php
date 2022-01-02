<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\updateProfileRequest;
use App\Models\Roles;
use App\Models\User;
use App\Traits\TraitResponse;
use Illuminate\Http\Request;

class Permission
{
    public $module, $tables, $permissions;
}
class AuthController extends Controller
{
    use TraitResponse;

    public function __construct()
    {
        $this->middleware('auth.jwt', ['except' => ['login']]);
    }
    public function login(LoginRequest $request)
    {

        if (!$token = auth()->attempt($request->validated())) {
            return $this->responseApi("", 401, "Tài khoản hoặc mật khẩu không đúng");
        }
        if (auth()->user()->status == 0) {
            $this->logout();
            return $this->responseApi("", 401, "Tài khoản chưa được kích hoạt");
        }

        return $this->createNewToken($token, 200, 'Tài khoản đăng nhập thành công !');
    }

    public function logout()
    {
        auth()->logout();
        return $this->responseApi("", 200, "Đăng xuất tài khoản thành công !");
    }

    public function changePassWord(ChangePasswordRequest $request)
    {
        $user = User::where('id', auth()->user()->id)->update(
            ['password' => bcrypt($request->new_password)]
        );

        return response()->json([
            'message' => 'Đổi mật khẩu thành công',
            'user' => $user,
        ], 201);
    }
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh(), "Thay đổi mã xác thực thành công");
    }

    public function userProfile(Request $request)
    {   
        $per = new Permission;
        $Users = auth()->user();
        $roles = Roles::find($Users->role_id);
        $module = [];
        $tables = [];
        $permission_data = [];
        $permissionsCheckeds = [];
        $permissionsCheckedFathers = [];
        $count = [];
        $count2 = [];
        foreach ($roles->permissions as $permissionsChildenItem) {

            if (!collect($count)->contains($permissionsChildenItem->permissionsFather->id)) {
                $count[] = $permissionsChildenItem->permissionsFather->id;
                $permissionsCheckedFathers[] = $permissionsChildenItem->permissionsFather;
            }
        }
        foreach ($permissionsCheckedFathers as $permissionsCheckedFather) {
            foreach ($roles->permissions as $permissionsChildenItem) {
                if ($permissionsChildenItem->parent_id == $permissionsCheckedFather->id) {
                    $permission_data[]=$permissionsChildenItem->key_code;
              
                }
            }
            $tables[]=$permissionsCheckedFather->name;
            $permissionsCheckeds["$permissionsCheckedFather->desc"][] = [
                "name" => $permissionsCheckedFather->name,
            ];
        }
        foreach ($permissionsCheckedFathers as $key => $modules) {
            if (!collect($count2)->contains($modules->desc)) {
                $count2[] = $modules->desc;
                foreach ($permissionsCheckeds as $key2 => $permission) {
                    if ($modules->desc == $key2) {
                        $module[]=$modules->desc;
                    }
                }
            }
        }


        $per->module = $module;
        $per->tables = $tables;
        $per->permissions = $permission_data;
        $Users->permission_data = $per;
        return $this->responseApi($Users, 200, "Thành công");
    }

    public function userUpdateProfile(updateProfileRequest $request)
    {
        $User = User::find(auth()->user()->id);
        if ($User) {
            $users = $User->update($request->validated());
            return $this->responseApi(User::find(auth()->user()->id), 200, "Cập nhật thông tin thành công");
        }
        return $this->responseApi("", 400, "Yêu cầu không chính xác");

    }

    protected function createNewToken($token, $message = "")
    {

        $per = new Permission;
        $Users = auth()->user();
        $roles = Roles::find($Users->role_id);
        $module = [];
        $tables = [];
        $permission_data = [];
        $permissionsCheckeds = [];
        $permissionsCheckedFathers = [];
        $count = [];
        $count2 = [];
        foreach ($roles->permissions as $permissionsChildenItem) {

            if (!collect($count)->contains($permissionsChildenItem->permissionsFather->id)) {
                $count[] = $permissionsChildenItem->permissionsFather->id;
                $permissionsCheckedFathers[] = $permissionsChildenItem->permissionsFather;
            }
        }
        foreach ($permissionsCheckedFathers as $permissionsCheckedFather) {
            foreach ($roles->permissions as $permissionsChildenItem) {
                if ($permissionsChildenItem->parent_id == $permissionsCheckedFather->id) {
                    $permission_data[]=$permissionsChildenItem->key_code;
              
                }
            }
            $tables[]=$permissionsCheckedFather->name;
            $permissionsCheckeds["$permissionsCheckedFather->desc"][] = [
                "name" => $permissionsCheckedFather->name,
            ];
        }
        foreach ($permissionsCheckedFathers as $key => $modules) {
            if (!collect($count2)->contains($modules->desc)) {
                $count2[] = $modules->desc;
                foreach ($permissionsCheckeds as $key2 => $permission) {
                    if ($modules->desc == $key2) {
                        $module[]=$modules->desc;
                    }
                }
            }
        }


        $per->module = $module;
        $per->tables = $tables;
        $per->permissions = $permission_data;
        $Users->permission_data = $per;
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => $message,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'user' => $Users,
        ]);
    }
}