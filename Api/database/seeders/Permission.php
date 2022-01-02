<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permissions;
class Permission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(config('permissions.permission') as $module){
            $permission = Permissions::create([
                'name' => $module["name"],
                'desc' => $module["desc"],
                'parent_id' => 0,
                'key_code' => ''
            ]);

            foreach ($module["permission_childen"] as $value_module) {
    
                Permissions::create([
                    'name' => $value_module["name"],
                    'desc' => "",
                    'parent_id' => $permission->id,
                    'key_code' => $value_module["key_code"],
    
                ]);
            }
            
        };
    }
}
