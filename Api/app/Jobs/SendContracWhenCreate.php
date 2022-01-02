<?php

namespace App\Jobs;

use App\Mail\SendContractEmail;
use App\Models\Bed;
use App\Models\ProjectService;
use App\Models\Room;
use App\Models\StudentInfo;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendContracWhenCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->data['student'] = User::where('id', $this->data['user_id'])->get(['first_name', 'last_name', 'birth', 'address', 'phone', 'email'])->toArray();
        $this->data['student_info'] = StudentInfo::where('user_id', $this->data['user_id'])->get(['CCCD', 'date_range', 'issued_by'])->toArray();
        if ($this->data['project_service_id'] != null) {
            foreach ($this->data['project_service_id'] as $p) {
                $this->data['project_service'][] = ProjectService::where('id', $p)->get(['name', 'unit_price', 'unit'])->toArray();
            }
            foreach ($this->data['project_service'] as $p) {
                $p[0]['unit_price'] = $this->VndDot($p[0]['unit_price']);
            }
        } else {
            $this->data['project_service'] = [];
        }

        $this->data['room'] = Room::with(['floor', 'floor.building', 'floor.building.project', 'room_type'])->find($this->data['room_id'])->toArray();
        $this->data['room']['room_type']['price_text'] = $this->VndText((int)$this->data['room']['room_type']['price']);

        $this->data['room']['room_type']['price'] = $this->VndDot(
            $this->data['room']['room_type']['price']
        );

        $this->data['room']['room_type']['price_deposit'] = $this->VndDot($this->data['room']['room_type']['price_deposit']);

        $this->data['bed'] = Bed::where('id', $this->data['bed_id'])->get(['name'])->toArray();
        try {
            Mail::to($this->data['student'][0]['email'])->send(new SendContractEmail($this->data));
            Log::info('Send contract for user email : ' . $this->data['student'][0]['email'] . ' success ');
            return true;
        } catch (\Throwable $th) {
            Log::info('Error - ' . $th);
            return false;
        }
    }
    public function VndText($amount)
    {
        if ($amount <= 0) {
            return $textnumber = "Tiền phải là số nguyên dương lớn hơn số 0";
        }
        $Text = array("không", "một", "hai", "ba", "bốn", "năm", "sáu", "bảy", "tám", "chín");
        $TextLuythua = array("", "nghìn", "triệu", "tỷ", "ngàn tỷ", "triệu tỷ", "tỷ tỷ");
        $textnumber = "";
        $length = strlen($amount);

        for ($i = 0; $i < $length; $i++)
            $unread[$i] = 0;

        for ($i = 0; $i < $length; $i++) {
            $so = substr($amount, $length - $i - 1, 1);

            if (($so == 0) && ($i % 3 == 0) && ($unread[$i] == 0)) {
                for ($j = $i + 1; $j < $length; $j++) {
                    $so1 = substr($amount, $length - $j - 1, 1);
                    if ($so1 != 0)
                        break;
                }

                if (intval(($j - $i) / 3) > 0) {
                    for ($k = $i; $k < intval(($j - $i) / 3) * 3 + $i; $k++)
                        $unread[$k] = 1;
                }
            }
        }

        for ($i = 0; $i < $length; $i++) {
            $so = substr($amount, $length - $i - 1, 1);
            if ($unread[$i] == 1)
                continue;

            if (($i % 3 == 0) && ($i > 0))
                $textnumber = $TextLuythua[$i / 3] . " " . $textnumber;

            if ($i % 3 == 2)
                $textnumber = 'trăm ' . $textnumber;

            if ($i % 3 == 1)
                $textnumber = 'mươi ' . $textnumber;


            $textnumber = $Text[$so] . " " . $textnumber;
        }

        //Phai de cac ham replace theo dung thu tu nhu the nay
        $textnumber = str_replace("không mươi", "lẻ", $textnumber);
        $textnumber = str_replace("lẻ không", "", $textnumber);
        $textnumber = str_replace("mươi không", "mươi", $textnumber);
        $textnumber = str_replace("một mươi", "mười", $textnumber);
        $textnumber = str_replace("mươi năm", "mươi lăm", $textnumber);
        $textnumber = str_replace("mươi một", "mươi mốt", $textnumber);
        $textnumber = str_replace("mười năm", "mười lăm", $textnumber);

        return ucfirst($textnumber . " đồng chẵn");
    }
    public  function VndDot($strNum)
    {
        $len = strlen($strNum);
        $counter = 3;
        $result = "";
        while ($len - $counter >= 0) {
            $con = substr($strNum, $len - $counter, 3);
            $result = '.' . $con . $result;
            $counter += 3;
        }
        $con = substr($strNum, 0, 3 - ($counter - $len));
        $result = $con . $result;
        if (substr($result, 0, 1) == '.') {
            $result = substr($result, 1, $len + 1);
        }
        return $result;
    }
    public function middleware()
    {
        return [new WithoutOverlapping($this->data['user_id'])];
    }
    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        Log::info('Send contract for user email  has encountered this error :  ' . $exception);
    }
}
