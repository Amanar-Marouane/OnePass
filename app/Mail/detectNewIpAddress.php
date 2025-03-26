<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class DetectNewIpAddress extends Mailable
{
    public $user;
    public $Ip;
    public $verificationCode;

    public function __construct($user, $newIp, $verificationCode)
    {
        $this->user = $user;
        $this->Ip = $newIp;
        $this->verificationCode = $verificationCode;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Detect new Ip Address',
        );
    }


    public function content(): Content
    {
        return new Content(
           view: 'emails.detectNewIpAdressMail',
           with: [
            'username' => $this->user->name,
            'newIp' => $this->Ip, 
            'verificationCode' => $this->verificationCode,
           ]
        );
    }

}
