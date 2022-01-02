<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateRoomHistoryInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $room_id;
    public function __construct($room_id)
    {
        $this->room_id = $room_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $currentYear =  Carbon::now()->format('Y');
        foreach (config('Months.months') as $month) {
            $insertDate = $month . '-' . $currentYear;
            $modInsertDate = date('Y-m-d H:i:s', strtotime($insertDate));
            $exitsRecord = DB::table('room_history_invoice')->where('created_at', $modInsertDate)->where('room_id', $this->room_id)->get();
            if ($exitsRecord->isNotEmpty()) {
                Log::warning("Attempt to insert the same room history invoice for room " . $this->room_id . ' and created_at ' . $modInsertDate . ' at ' . Carbon::now());
                return false;
            } else {
                DB::table('room_history_invoice')->insert(
                    [
                        'room_id' => $this->room_id,
                        'status' => config('Months.status.chua_chot'),
                        'created_at' => $modInsertDate
                    ]
                );
            }
        }
        return true;
    }
    public function middleware()
    {
        return [new WithoutOverlapping($this->room_id . ' invoice')];
    }
    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        Log::info('Create room for room id : ' . $this->room_id . ' has encountered this error :  ' . $exception);
    }
}
