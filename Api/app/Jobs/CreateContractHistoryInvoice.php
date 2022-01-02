<?php

namespace App\Jobs;

use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateContractHistoryInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $contract_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($contract_id)
    {
        $this->contract_id = $contract_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $contract = Contract::find($this->contract_id);
        $cycle_collect = $contract->room->floor->building->project->cycle_collect;
        $total_date = Carbon::parse($contract->start_day)->diffInMonths(Carbon::parse($contract->end_day));
        $monthWillAdd = round($total_date / $cycle_collect);
        $insertDate = Carbon::parse($contract->start_day);
        for ($i = 0; $i < $monthWillAdd; $i++) {
            $modInsertDate = $insertDate->format('Y-m-d H:i:s');
            $exitsRecord = DB::table('contract_history_invoice')->where('created_at', $modInsertDate)->where('contract_id', $contract->id)->get();
            if ($exitsRecord->isNotEmpty()) {
                Log::warning("Attempt to insert the same contract history invoice for contract " . $contract->id . ' and created_at ' . $modInsertDate . ' at ' . Carbon::now());
                return false;
            } else {
                DB::table('contract_history_invoice')->insert(
                    [
                        'contract_id' => $contract->id,
                        'status' => config('Months.status.chua_chot'),
                        'created_at' => $modInsertDate
                    ]
                );
            }
            $insertDate = $insertDate->addMonths($cycle_collect);
        }
        return true;
    }
    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        Log::info('Create room for room id : ' . $this->contract_id . ' has encountered this error :  ' . $exception);
    }
}
