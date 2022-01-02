<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
class AnnouncementSeeder extends Seeder
{
    
    public function run()
    {
        Announcement::factory(20)->create();
    }
}
