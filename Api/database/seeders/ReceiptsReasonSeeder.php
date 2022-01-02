<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Receipts_reason;
class ReceiptsReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Receipts_reason::factory()->count(50)->create();
    }
}
