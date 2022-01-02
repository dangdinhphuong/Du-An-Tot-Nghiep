<?php

namespace Database\Seeders;

use App\Models\Unit_asset;
use Illuminate\Database\Seeder;

class Unit_assetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit_asset::factory(20)->create();
    }
}
