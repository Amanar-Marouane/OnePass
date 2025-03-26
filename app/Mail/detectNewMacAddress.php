<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class DetectNewMacAddress extends Mailable
{
    public function __construct() {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Detect New Device',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.detectNewMacAdressMail',

        );
    }
}
