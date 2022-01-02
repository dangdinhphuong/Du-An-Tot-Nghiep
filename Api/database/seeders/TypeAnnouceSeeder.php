<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeAnnounce;
class TypeAnnouceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeAnnounce::factory()->count(30)->create();
    }
}
