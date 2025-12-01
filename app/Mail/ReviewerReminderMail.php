<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Paper;
use App\Models\User;

class ReviewerReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reviewerName;
    public $articleTitle;
    public $deadline;
    public $articleUrl;
    public $editorName;
    public $paper;
    public $reviewer;

    public $subjectText;   // ← custom subject
    public $emailBody;     // ← custom email message

    public function __construct(
        Paper $paper, 
        User $reviewer, 
        $editorName,
        $subjectText = null,
        $emailBody = null
    ) {
        $this->paper       = $paper;
        $this->reviewer    = $reviewer;
        $this->editorName  = $editorName;

        $this->reviewerName = trim(($reviewer->first_name ?? '') . ' ' . ($reviewer->last_name ?? ''));

        $this->articleTitle = $paper->judul ?? 'Untitled';

        // Ambil deadline dari pivot table
        $pivotDeadline = optional(
            $paper->reviewers()->where('users.id', $reviewer->id)->first()
        )->pivot->deadline ?? null;

        $this->deadline = $pivotDeadline ?? 'No deadline specified';

        $this->articleUrl = $paper->file_path 
                                ? asset('storage/' . $paper->file_path) 
                                : '#';

        // Custom subject & email body
        $this->subjectText = $subjectText 
                                ?? "Reminder: Review on Manuscript {$this->articleTitle}";

        $this->emailBody   = $emailBody 
                                ?? "This is a gentle reminder regarding your pending review.";
    }

    public function build()
    {
        $names = trim($this->reviewer->first_name . ' ' . $this->reviewer->last_name);

        $email = $this->subject($this->subjectText)
                      ->view('emails.reviewer_reminder')
                      ->with([
                          'names'        => $names,
                          'paper'        => $this->paper,
                          'deadline'     => $this->deadline,
                          'articleUrl'   => $this->articleUrl,
                          'editorName'   => $this->editorName,
                          'articleTitle' => $this->articleTitle,
                          'emailBody'    => $this->emailBody,
                          'reviewerName' => $this->reviewerName,
                      ]);

        // Attach article
        if ($this->paper->file_path && file_exists(storage_path('app/public/' . $this->paper->file_path))) {
            $email->attach(storage_path('app/public/' . $this->paper->file_path));
        }

        return $email;
    }
}