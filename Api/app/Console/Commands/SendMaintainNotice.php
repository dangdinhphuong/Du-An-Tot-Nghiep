<?php

namespace App\Console\Commands;

use App\Mail\MaintainEmail;
use App\Mail\UnderTakeEmail;
use App\Models\Maintenance;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMaintainNotice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintain:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $maintainUsers = Maintenance::with('userCreate')->with('userUnderTake')->whereNotNull('periodic')->get();
        foreach ($maintainUsers as $maintainUser) {
            $currentDate =  Carbon::now();
            $datePassed = 1;
            if ($maintainUser->date_end > $currentDate->format('Y-m-d') && $maintainUser->status != config('maintain.status.Da_hoan_thanh')) {
                if ($maintainUser->cycle_date != null) {
                    $datePassed = $currentDate->diffInDays($maintainUser->cycle_date);
                }
                if ($datePassed < 1) {
                    Log::info($maintainUser->userCreate->email . ' và ' . $maintainUser->userUnderTake->email . 'Ngày gửi tiếp theo chưa đến 1 ngày');;
                } else if ($datePassed == 1) {
                    Log::info($maintainUser->userCreate->email . ' và ' . $maintainUser->userUnderTake->email . 'Đã đến hạn gửi');;
                    try {
                        Mail::to($maintainUser->userCreate->email)->send(new MaintainEmail($maintainUser));
                        Mail::to($maintainUser->userUnderTake->email)->send(new UnderTakeEmail($maintainUser));
                        Log::info($maintainUser->userCreate->email . ' và ' . $maintainUser->userUnderTake->email . 'Mail send successfully');
                        $maintainUser->cycle_date = Carbon::now()->addDays()->format('Y-m-d');
                        $maintainUser->save();
                        Log::info('Change cycle date successfully');
                    } catch (\Exception $e) {
                        Log::info('Error - ' . $e);
                    }
                } else if ($datePassed > 1) {
                    Log::info($maintainUser->userCreate->email . ' và ' . $maintainUser->userUnderTake->email . "Ngày gửi tiếp theo quá 1 ngày");
                    try {
                        Mail::to($maintainUser->userCreate->email)->send(new MaintainEmail($maintainUser));
                        Mail::to($maintainUser->userUnderTake->email)->send(new UnderTakeEmail($maintainUser));
                        Log::info($maintainUser->userCreate->email . ' và ' . $maintainUser->userUnderTake->email . 'Mail send successfully');
                        $maintainUser->cycle_date = Carbon::now()->addDays()->format('Y-m-d');
                        $maintainUser->save();
                        Log::info($maintainUser->userCreate->email . ' và ' . $maintainUser->userUnderTake->email . 'Change cycle date successfully');
                    } catch (\Exception $e) {
                        Log::info('Error - ' . $e);;
                    }
                };
            } else {
                Log::info('Đã hết thời gian của công việc tên : ' . $maintainUser->name . ' với id: ' . $maintainUser->id);;
            }
        };
    }
}
