<?php

namespace Database\Seeders;

use App\Models\Asset_type;
use Illuminate\Database\Seeder;

class Asset_typeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Asset_type::factory(20)->create();
    }
}
