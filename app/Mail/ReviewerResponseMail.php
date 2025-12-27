<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ReviewerResponseMail extends Mailable
{
    public $subjectText;
    public $bodyHtml;

    /**
     * Create a new message instance.
     */
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

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reviewer Response Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
