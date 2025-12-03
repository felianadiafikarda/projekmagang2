<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Paper;
use App\Models\User; 

class ReviewerAssignmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $paper;
    public $reviewer;
    public $deadline;
    public $articleUrl;
    public $editorName;
    public $articleTitle;
    public $invitationUrl;

    public $subjectText;   
    public $emailBody;     

    public function __construct(
        Paper $paper, 
        User $reviewer, 
        $deadline, 
        $editorName,
        $subjectText = null, 
        $emailBody = null,
        $invitationUrl = null
    ) {
        $this->paper        = $paper;
        $this->reviewer     = $reviewer;
        $this->deadline     = $deadline;
        $this->editorName   = $editorName;
        $this->invitationUrl = $invitationUrl ?? '#';

        $this->articleTitle = $paper->judul;
        $this->articleUrl   = $paper->file_path 
                                ? asset('storage/' . $paper->file_path) 
                                : '#';

        // If editor doesn't define subject/body → fallback default
        $this->subjectText  = $subjectText 
                                ?? "Invitation to Review : {$paper->judul}";

        $this->emailBody    = $emailBody 
                                ?? "You are invited to review the manuscript assigned to you.";
    }

    public function build()
    {
        $names = $this->reviewer->first_name . ' ' . $this->reviewer->last_name;

        $email = $this->subject($this->subjectText)
            ->view('emails.reviewer_assignment')
            ->with([
                'names'        => $names,
                'paper'        => $this->paper,
                'deadline'     => $this->deadline,
                'articleUrl'   => $this->articleUrl,
                'editorName'   => $this->editorName,
                'articleTitle' => $this->articleTitle,
                'emailBody'    => $this->emailBody,
                'invitationUrl' => $this->invitationUrl,
            ]);

        if ($this->paper->file_path && file_exists(storage_path('app/public/' . $this->paper->file_path))) {
            $email->attach(storage_path('app/public/' . $this->paper->file_path));
        }

        return $email;
    }

}