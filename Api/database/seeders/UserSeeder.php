<?php

namespace Database\Seeders;

use App\Models\StudentInfo;
use App\Models\StudentRelative;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "first_name" => "",
            "last_name" => "admin",
            "birth" => "2009-12-26",
            "birth_place" => "Vô danh",
            "gender" => 1,
            "address" => "Vô danh",
            "phone" => "012345678",
            "user_type" => 1,
            "email" => "lechuhuuha@gmail.com",
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            "role_id" => 3,
            "status" => "1"
        ]);
        User::factory()->count(50)->create();
        StudentInfo::factory()->count(50)->create();
        StudentRelative::factory()->count(50)->create();
    }
}
