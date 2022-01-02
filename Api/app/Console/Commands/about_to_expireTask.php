<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tasks;
use App\Mail\SendMail;
class about_to_expireTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'about_to_expireTask:cron';

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
        $tasks = Tasks::all();
        $tasks->load('user_create');
        $tasks->load('user_undertake');
        foreach($tasks as $task){
            if($task->status!=4)
            {
                $date = date_create($task->date_end);
                if(strtotime("+2 day", strtotime(date('Y-m-d')))==strtotime(date_format($date, 'Y-m-d')))
                {
                    SendMail::Send([$task->user_undertake->email],['title'=>"Nhiệm vụ sắp hết hạn","tasks"=>$task]);
                   }

               }
               if(strtotime(date('Y-m-d'))>strtotime(date_format($date, 'Y-m-d')))
                {
                    SendMail::Send([$task->user_undertake->email],['title'=>"Nhiệm vụ của bạn hết hạn","tasks"=>$task]);
                    $task->update(['status'=>4]);// update  processed

               }
            
        }
        // return Command::SUCCESS;
    }
}