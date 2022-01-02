<?php

namespace App\Console;

use App\Console\Commands\about_to_expireTask;
use App\Console\Commands\SendMaintainNotice;
use App\Console\Commands\SendOutdateContractEmail;
use App\Console\Commands\senMailTask;
use App\Models\Maintenance;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('queue:restart')
        //     ->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('queue:work')
            ->everyMinute()
            ->withoutOverlapping();
        $schedule->command(SendMaintainNotice::class)->everyMinute()->withoutOverlapping();
        $schedule->command(SendOutdateContractEmail::class)->daily()->withoutOverlapping();
        $schedule->command(senMailTask::class)->everyMinute()->withoutOverlapping();
        $schedule->command(about_to_expireTask::class)->daily()->withoutOverlapping();
        $schedule->command('php artisan config:clear')->weekly();
        Log::info("Success at " . Carbon::now());
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
