<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTestMail extends Mailable
{
    use Queueable, SerializesModels;

    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function build()
    {
        return $this->view('mail.register')
            ->from('kt1206hp@gmail.com','Tomoya')
            ->subject('下記のurlから登録を完了してください。')
            ->with(['url' => $this->url]);
    }
}
