<?php

namespace App\Console\Commands;

use App\Mail\SendContractOutdateEmail;
use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOutdateContractEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contract:outdate';

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
        $data = Contract::all();
        foreach ($data as $d) {
            $now = Carbon::now();
            $end_day =  Carbon::createFromFormat('Y-m-d H:s:i', $d->end_day);
            $diff =  $end_day->diffInMonths($now);
            $da['name'] = $d->user->first_name . ' ' . $d->user->last_name;
            $da['email'] = $d->user->email;
            $da['contract'] = $d;
            if ($diff == 1) {
                $da['outdate_day'] = $diff . ' tháng';
                try {
                    Mail::to($da['email'])->send(new SendContractOutdateEmail($da));
                    Log::info('Gửi cho sinh viên ' . $da['email'] . ' tên nhà ' . $da['contract']['room']->name);
                    return Command::SUCCESS;
                } catch (\Throwable $th) {
                    Log::error('Gửi cho sinh viên ' . $da['email'] . ' tên nhà ' . $da['contract']['room']->name);

                    Log::info('Error - ' . $th);
                }
            } elseif ($diff == 0) {
                $diff =  $now->diffInDays($end_day);
                if ($diff <= 5) {
                    $da['outdate_day'] = $diff . ' ngày';
                    try {
                        Mail::to($da['email'])->send(new SendContractOutdateEmail($da));
                        Log::info('Gửi cho sinh viên ' . $da['email'] . ' tên nhà ' . $da['contract']['room']->name);
                        return Command::SUCCESS;
                    } catch (\Throwable $th) {
                        Log::error('Gửi cho sinh viên ' . $da['email'] . ' tên nhà ' . $da['contract']['room']->name);
                        Log::error('Error - ' . $th);
                    }
                } elseif ($diff <= 7 && $diff >= 6) {
                    $da['outdate_day'] = $diff . ' ngày';
                    try {
                        Mail::to($da['email'])->send(new SendContractOutdateEmail($da));
                        Log::info('Gửi cho sinh viên ' . $da['email'] . ' tên nhà ' . $da['contract']['room']->name);
                        return Command::SUCCESS;
                    } catch (\Throwable $th) {
                        Log::error('Gửi cho sinh viên ' . $da['email'] . ' tên nhà ' . $da['contract']['room']->name);

                        Log::error('Error - ' . $th);
                    }
                } else {
                    return Command::SUCCESS;
                }
            }
        }
        return Command::SUCCESS;
    }
}
