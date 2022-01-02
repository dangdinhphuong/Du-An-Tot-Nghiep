<?php

use App\Models\Roles;
use Illuminate\Support\Facades\Auth;

if (!function_exists('baseGate')) {
    function baseGate($roleName = '')
    {

        $role = Roles::find(auth()->user()->role_id);
        //   dd($roleName,$role->permissions[0]);
        $permissions = $role->permissions;
        $arr = [];
        foreach ($permissions as $permission) {
            $arr[] = $permission->key_code;
            if ($permission->key_code == $roleName) {
                return true;
            }
            //return false;
        }
    }
}
