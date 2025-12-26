<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaperDecisionMail extends Mailable
{
    public $paper;
    public $author;
    public $status;
    public $editorName;
    public $reviewFiles;
    public string $emailBody;

    public function __construct(
        $paper,
        $author,
        $status,
        $editorName,
        $reviewFiles = [],
        string $emailBody = ''
    ) {
        $this->paper       = $paper;
        $this->author      = $author;
        $this->status      = $status;
        $this->editorName  = $editorName;
        $this->reviewFiles = $reviewFiles;
        $this->emailBody   = $emailBody;
    }
    

    public function build()
{
    $subject = match ($this->status) {
        'accepted'           => 'Accepted',
        'rejected'           => 'Rejected',
        'accept_with_review' => 'Accept with Review',
        default              => 'Pending Decision',
    };

    $mailable = $this->subject($subject)
        ->view('emails.paper_decision')
        ->with([
            'emailBody' => $this->emailBody,
        ]);

    if (!empty($this->reviewFiles)) {
        foreach ($this->reviewFiles as $file) {
            if (is_array($file) && isset($file['path'], $file['name']) && file_exists($file['path'])) {
                $mailable->attach($file['path'], [
                    'as' => $file['name']
                ]);
            }
        }
    }

    return $mailable;
}

}