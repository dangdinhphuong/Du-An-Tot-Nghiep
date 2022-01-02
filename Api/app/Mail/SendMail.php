<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
class Send extends Mailable
{
    use Queueable, SerializesModels;
public $content;

    public function __construct($content)
    {   
        $this->content=$content;
    }

    public function build()
     {  $data=$this->content;
        
        if (isset($this->content['view']) && !empty($this->content['view'])){
           
            return $this->subject($this->content['title'])->view($this->content['view'],compact("data"));
        }
        
        return $this->subject($this->content["title"])->view('Mail.TaskMail',compact("data"));
        
    }
}

class SendMail{
    public function Send($address,$content)
    {
        foreach ($address as $addres) { 
          Mail::to($addres)->queue(new Send($content));
        }
    } 
}
