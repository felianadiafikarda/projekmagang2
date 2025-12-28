<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ReviewerResponseMail extends Mailable
{
    public $subjectText;
    public $bodyHtml;

    public function __construct($subjectText, $bodyHtml)
    {
        $this->subjectText = $subjectText;
        $this->bodyHtml    = $bodyHtml;
    }

     public function build()
    {
        return $this->subject($this->subjectText)
            ->html($this->bodyHtml);
    }
}
