<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tasks;
use App\Mail\SendMail;
use App\Models\Announcement;
use App\Models\StudentInteract;
use Illuminate\Support\Facades\Log;

class senMailTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'senMailTask:cron';

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
        foreach ($tasks as $task) {

            if (
                strtotime(date('Y-m-d H:i:s')) < strtotime($task->date_end)
                && $task->processed != config('Tasks.processed')['old']
                && $task->status != 4
            ) {
                Announcement::create([
                    'level' => $task->priority,
                    'title' => $task->title,
                    'cotent' => $task->desc,
                    'user_id' => $task->user_undertake_id,
                    'type_announce_id' => 1,
                    'range' => config('User.range')[1]["range_id"]
                ]);
                SendMail::Send([$task->user_undertake->email], ['title' => "Nhiệm vụ mới", "tasks" => $task]);
                $task->update(['processed' => config('Tasks.processed')['old']]); // update  processed

            } else {
                Log::debug('Not working');
            }
        }
        // return Command::SUCCESS;

        $studentInteract = StudentInteract::where('check', '!=', 1)->get();

        $studentInteract->load('staff');
        $studentInteract->load('student');

        $requestType = config('User.StudentInter.requestType')[3]['requestType_id'];
        foreach ($studentInteract as $studentInter) {
            if ($studentInter->check === 0 && $studentInter->status !== $requestType) {
                SendMail::Send([$studentInter->student->email], ['title' => "Tương tác mới ", "view" => "Mail.StudentInteractMail", "studentInteract" => $studentInteract]);
                $studentInter->update(['check' => "1"]);
            } elseif ($studentInter->check === 2 && $studentInter->status !== $requestType) {
                SendMail::Send([$studentInter->student->email], ['title' => "Tương tác mới được cập nhật ", "view" => "Mail.StudentInteractMail", "studentInteract" => $studentInteract]);
                $studentInter->update(['check' => "1"]);
            } else {
                Log::debug('Not working');
            }
        }
    }
}
