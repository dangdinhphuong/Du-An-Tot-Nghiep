<?php

namespace Database\Seeders;

use App\Models\Floor;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Room::factory()->count(400)->create();
        $floors = Floor::all();
        foreach ($floors as $floor) {
            $room_types = $floor->building->project->room_type;
            if ($room_types != null) {
                foreach ($room_types as $room_type) {
                    for ($i = 0; $i < 10; $i++) {
                        $id =  DB::table('rooms')->insertGetId(
                            [
                                'name' => Str::random(10),
                                'room_type_id' => $room_type->id,
                                'floor_id' => $floor->id
                            ]
                        );
                        $this->insertServiceIndex($id);
                    }
                }
            }
        }
    }
    public function insertServiceIndex($id)
    {
        $currentYear =  Carbon::now()->format('Y');
        foreach (config('Months.months') as $month) {
            try {
                $insertDate = $month . '-' . $currentYear;
                $modInsertDate = date('Y-m-d H:i:s', strtotime($insertDate));
                $exitsRecord = DB::table('service_indexes')->where('created_at', $modInsertDate)->where('room_id', $id)->get();
                if ($exitsRecord->isNotEmpty()) {
                    Log::warning("Attempt to insert the same service index for room " . $id . ' and created_at ' . $modInsertDate . ' at ' . Carbon::now());
                } else {
                    DB::table('service_indexes')->insert(
                        [
                            'room_id' => $id,
                            'status' => config('Months.status.chua_chot'),
                            'created_at' => $modInsertDate
                        ]
                    );
                }
            } catch (\Throwable $th) {
                Log::info($th);
            }
        }
    }
}
