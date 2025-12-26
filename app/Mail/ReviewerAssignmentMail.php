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
    public $paperFile;
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
        
        $this->paperFile = $paper->file_path
            ? storage_path('app/public/' . $paper->file_path)
            : null;
    }

    public function build()
    {
        $names = $this->reviewer->first_name . ' ' . $this->reviewer->last_name;

        $email = $this->from(
            config('mail.from.address'),
            'Assign Reviewer'
            )
            ->subject($this->subjectText)
            ->view('emails.reviewer_assignment')
            ->with([
                'names'         => $names,
                'paper'         => $this->paper,
                'deadline'      => $this->deadline,
                'articleUrl'    => $this->articleUrl,
                'editorName'    => $this->editorName,
                'articleTitle'  => $this->articleTitle,
                'emailBody'     => $this->emailBody,
                'invitationUrl' => $this->invitationUrl,
            ]);

            if ($this->paperFile && file_exists($this->paperFile)) {
                $email->attach($this->paperFile, [
                    'as' => $this->paper->judul . '.pdf',
                ]);
            }

        return $email;
    }

}