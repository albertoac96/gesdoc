<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class newUser extends Mailable
{
    use Queueable, SerializesModels;

    public $infoUser;
    public $pass;
    public $url;

    public function __construct($infoUser, $pass, $url)
    {
        $this->infoUser = $infoUser;
        $this->pass = $pass;
        $this->url = $url;
    }

    public function build()
    {
        return $this->subject('Has sido registrado como usuario del SGD-DEH')
        ->view('mails.newUser');
    }
}
