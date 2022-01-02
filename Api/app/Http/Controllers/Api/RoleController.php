<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\addPermissionToRoleRequest;
use App\Http\Requests\RoleStoreRequest;
use App\Models\Permissions;
use App\Models\Roles;
use App\Traits\TraitResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    use TraitResponse;
    private $permissions;
    private $roles;
    public function __construct(Permissions $permissions, Roles $roles)
    {
        $this->permissions = $permissions;
        $this->roles = $roles;
    }

    public function index(Request $request)
    {

        $data = $this->roles->all();

        return $this->responseApi($data, 200);
    }

    public function create()
    {

        $permissions_parents = $this->permissions->where('parent_id', 0)->get();
        $data = [];

        foreach ($permissions_parents as $permissions_parent) {
            $action = [];

            foreach ($permissions_parent->permissionsChilden as $permissionsChildenItem) {
                $action[] = [
                    'id' => $permissionsChildenItem->id,
                    'name' => $permissionsChildenItem->name,
                ];
            }
            $data[] = [
                'module_name' => $permissions_parent->name,
                'desc' => $permissions_parent->desc,
                'action' => $action,
            ];
        }

        return $this->responseApi($data, 200);
    }

    public function store(RoleStoreRequest $request)
    {
        $string = $request->role_name;
        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
            '#(ì|í|ị|ỉ|ĩ)#',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#',
            '#(đ)#',
            '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
            '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
            '#(Ì|Í|Ị|Ỉ|Ĩ)#',
            '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
            '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
            '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
            '#(Đ)#',
            "/[^a-zA-Z0-9\-\_]/",
        );
        $replace = array(
            'a',
            'e',
            'i',
            'o',
            'u',
            'y',
            'd',
            'A',
            'E',
            'I',
            'O',
            'U',
            'Y',
            'D',
            '-',
        );
        $string = preg_replace($search, $replace, $string);
        $string = preg_replace('/(-)+/', ' ', $string);
        $string = ucwords(strtolower($string));
        try {
            DB::beginTransaction();
            $role = $this->roles->create([
                'name' => $string,
                'desc' => $request->role_desc,
            ]);

            // $role->permissions()->attach($request->permission_id);
            DB::commit();
            return $this->responseApi($role, 200, 'Thêm mới chức vụ thành công !');
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->responseApi("", 400, 'Thêm mới chức vụ thất bại !');
        }
    }
    public function addPermissionToRole(addPermissionToRoleRequest $request)
    {
        $roles = $this->roles->find($request->role_id);

        if (isset($roles) && !empty($roles)) {
            $roles->permissions()->attach($request->permission_id);
            return $this->responseApi("", 200, "Thêm chức năng cho chức chức vụ thành công !");
        } else {

            return $this->responseApi("", 400, "lỗi cú pháp trong yêu cầu");
        }
    }
    public function edit(Request $request, $id)
    {

        $roles = $this->roles->find($id);

        if (isset($roles) && !empty($roles)) {
            $permissions_parents = $this->permissions->where('parent_id', 0)->get();
            $permissions = [];
            foreach ($permissions_parents as $permissions_parent) {
                $action = [];
                foreach ($permissions_parent->permissionsChilden as $permissionsChildenItem) {
                    $action[] = [
                        'id' => $permissionsChildenItem->id,
                        'name' => $permissionsChildenItem->name,
                    ];
                }
                $permissions[] = [
                    'module_name' => $permissions_parent->name,
                    'desc' => $permissions_parent->desc,
                    'action' => $action,
                ];
            }

            $permissionsCheckeds = [];
            foreach ($roles->permissions as $permissionsCheckedItem) {
                $permissionsCheckeds[] =
                $permissionsCheckedItem->id;
            }
            return $this->responseApi(
                [
                    'permissions' => $permissions,
                    'permission_Checked' => $permissionsCheckeds,
                ],
                200
            );
        } else {

            return $this->responseApi("", 400, "lỗi cú pháp trong yêu cầu");
        }
    }

    public function update(RoleStoreRequest $request, $id)
    {
        $string = $request->role_name;
        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
            '#(ì|í|ị|ỉ|ĩ)#',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#',
            '#(đ)#',
            '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
            '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
            '#(Ì|Í|Ị|Ỉ|Ĩ)#',
            '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
            '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
            '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
            '#(Đ)#',
            "/[^a-zA-Z0-9\-\_]/",
        );
        $replace = array(
            'a',
            'e',
            'i',
            'o',
            'u',
            'y',
            'd',
            'A',
            'E',
            'I',
            'O',
            'U',
            'Y',
            'D',
            '-',
        );
        $string = preg_replace($search, $replace, $string);
        $string = preg_replace('/(-)+/', ' ', $string);
        $string = ucwords(strtolower($string));
        $roles = $this->roles->find($id);
        if (isset($roles) && !empty($roles)) {
            if ($roles->status == 1) {
                return $this->responseApi("", 400, " Bạn không thể sửa chức vụ này");
            }
            try {
                DB::beginTransaction();
                $roles->update([
                    'name' => $string,
                    'desc' => $request->role_desc,
                ]);

                // $roles->permissions()->sync($request->permission_id); // upload update array to role_user ===> 'sync'
                DB::commit();
                return $this->responseApi("", 200, 'Cập nhật chức vụ thành công !');
            } catch (Exception $exception) {
                DB::rollBack();
            }
        }

        return $this->responseApi("", 400, "Cập nhật chức vụ thất bại");
    }
    public function updatePermissionToRole(addPermissionToRoleRequest $request)
    {
        $roles = $this->roles->find($request->role_id);

        if (isset($roles) && !empty($roles)) {
            $roles->permissions()->sync($request->permission_id);
            $table_module = array();
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
                $action = [];
                foreach ($roles->permissions as $permissionsChildenItem) {
                    if ($permissionsChildenItem->parent_id == $permissionsCheckedFather->id) {
                        $action[] = [
                            'id' => $permissionsChildenItem->id,
                            'name' => $permissionsChildenItem->name,
                        ];
                    }
                }
                $permissionsCheckeds["$permissionsCheckedFather->desc"][] = [
                    "name" => $permissionsCheckedFather->name,
                    'permissions' => $action,
                ];
            }
            foreach ($permissionsCheckedFathers as $key => $modules) {
                if (!collect($count2)->contains($modules->desc)) {
                    $count2[] = $modules->desc;
                    foreach ($permissionsCheckeds as $key2 => $permission) {
                        if ($modules->desc == $key2) {
                            $table_module[] = [
                                'name' => $modules->desc,
                                'tables' => $permission,
                            ];
                        }
                    }
                }
            }
            return $this->responseApi($table_module, 200, "Cập nhật chức năng cho chức vụ thành công !");
        } else {

            return $this->responseApi("", 400, "lỗi cú pháp trong yêu cầu");
        }
    }
    public function destroy(Request $request, $id)
    {
        $roles = $this->roles->find($id);
        if (isset($roles) && !empty($roles)) {

            if ($roles->status == 1) {
                return $this->responseApi("", 400, " Bạn không thể xóa chức vụ này");
            }
            $roles->delete();
            return $this->responseApi("", 200, "xóa role thành công");
        }

        return $this->responseApi("", 400, "Lỗi cú pháp trong yêu cầu");
    }

    public function getRoleById(Request $request, $id)
    {

        $roles = $this->roles->find($id);

        if (isset($roles) && !empty($roles)) {
            $table_module = array();
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
                $action = [];
                foreach ($roles->permissions as $permissionsChildenItem) {
                    if ($permissionsChildenItem->parent_id == $permissionsCheckedFather->id) {
                        $action[] = [
                            'id' => $permissionsChildenItem->id,
                            'name' => $permissionsChildenItem->name,
                        ];
                    }
                }
                $permissionsCheckeds["$permissionsCheckedFather->desc"][] = [
                    "name" => $permissionsCheckedFather->name,
                    'permissions' => $action,
                ];
            }
            foreach ($permissionsCheckedFathers as $key => $modules) {
                if (!collect($count2)->contains($modules->desc)) {
                    $count2[] = $modules->desc;
                    foreach ($permissionsCheckeds as $key2 => $permission) {
                        if ($modules->desc == $key2) {
                            $table_module[] = [
                                'name' => $modules->desc,
                                'tables' => $permission,
                            ];
                        }
                    }
                }
            }

            return $this->responseApi(
                $table_module,

                200
            );
        } else {

            return $this->responseApi("", 400, "lỗi cú pháp trong yêu cầu");
        }
    }
    public function getRoleAll(Request $request)
    {

        $roles = $this->roles->find(2);

        if (isset($roles) && !empty($roles)) {

            $permissions_parents = $this->permissions->where('parent_id', 0)->orderBy('id', 'DESC')->get();
            $module = $this->permissions->where('parent_id', 0)->groupBy('desc')->orderBy('id', 'DESC')->get();
            $permissions = array();
            $table_module = array();

            foreach ($permissions_parents as $permissions_parent) {
                $action = [];
                foreach ($permissions_parent->permissionsChilden as $permissionsChildenItem) {
                    $action[] = [
                        'id' => $permissionsChildenItem->id,
                        'name' => $permissionsChildenItem->name,
                    ];
                }

                $permissions["$permissions_parent->desc"][] = [
                    "name" => $permissions_parent->name,
                    'permissions' => $action,

                ];
            }

            foreach ($module as $key => $modules) {

                foreach ($permissions as $key2 => $permission) {
                    if ($modules->desc == $key2) {
                        $table_module[] = [
                            'name' => $modules->desc,
                            'tables' => $permission,
                        ];
                    }
                }
            }

            $permissionsCheckeds = [];
            foreach ($roles->permissions as $permissionsCheckedItem) {
                $permissionsCheckeds[] = $permissionsCheckedItem;
            }

            return $this->responseApi($permissionsCheckeds, 200);
        } else {

            return $this->responseApi("", 400, "lỗi cú pháp trong yêu cầu");
        }
    }
    public function getPermissionAll(Request $request)
    {

        $permissions_parents = $this->permissions->where('parent_id', 0)->orderBy('id', 'DESC')->get();
        $module = $this->permissions->where('parent_id', 0)->groupBy('desc')->orderBy('id', 'DESC')->get();
        $permissions = array();
        $table_module = array();
        $permission_id = array();
        Permissions::where('parent_id', '>', '0')->get()->map(function ($permission) {
            //   dd($permission->id);

        });

        foreach ($permissions_parents as $permissions_parent) {
            $action = [];
            foreach ($permissions_parent->permissionsChilden as $permissionsChildenItem) {
                $action[] = [
                    'id' => $permissionsChildenItem->id,
                    'name' => $permissionsChildenItem->name,
                ];
            }

            $permissions["$permissions_parent->desc"][] = [
                "name" => $permissions_parent->name,
                'permissions' => $action,

            ];
        }

        foreach ($module as $key => $modules) {

            foreach ($permissions as $key2 => $permission) {
                if ($modules->desc == $key2) {
                    $table_module[] = [
                        'name' => $modules->desc,
                        'tables' => $permission,
                    ];
                }
            }
        }

        return $this->responseApi($table_module, 200);
    }
}
