<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Receipt;
use App\Models\Receipts_reason;
class ReceiptsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Receipts_reason::factory()->count(50)->create();
        Receipt::factory()->count(50)->create();
    }
}
