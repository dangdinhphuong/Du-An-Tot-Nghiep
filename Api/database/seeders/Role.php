<?php

namespace Database\Seeders;

use App\Models\Permissions;
use Illuminate\Database\Seeder;
use App\Models\Roles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class Role extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Roles::create([
            'name' => "Mac Dinh",
            'desc' => "Tài khoản chưa được phân chức vụ",
            'status' => 1

        ]);
        $studentPermissions = Permissions::whereIn('key_code', config('permissions.studentPermissions'))->get();
        $studentData = [];
        foreach ($studentPermissions as $p) {
            $studentData[] = $p->id;
        };
        $permissions = Permissions::where('parent_id', '!=', 0)->get('id');
        $data = [];
        foreach ($permissions as $p) {
            $data[] = $p->id;
        };
        try {
            DB::beginTransaction();
            $studentRole =  Roles::create([
                'name' => "Sinh viên",
                'desc' => "Tài khoản của sinh viên",
                'status' => 1,


            ]);
            $studentRole->permissions()->attach($studentData);
            $role =  Roles::create([
                'name' => "ADMIN",
                'desc' => "Tài khoản của admin",
                'status' => 1,
            ]);
            $role->permissions()->attach($data);
            DB::commit();
        } catch (Exception $exception) {
            Log::info($exception);
            DB::rollBack();
        }
    }
}
