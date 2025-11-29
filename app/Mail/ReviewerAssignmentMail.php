<?php

// app/Mail/ReviewerAssignmentMail.php
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

    public function __construct(Paper $paper, User $reviewer, $deadline, $editorName)
    {
        $this->paper = $paper;
        $this->reviewer = $reviewer;
        $this->deadline = $deadline;
        $this->editorName = $editorName;
        $this->articleTitle = $paper->judul;
        $this->articleUrl = $paper->file_path ? asset('storage/' . $paper->file_path) : '#';
    }

    public function build()
    {
        $names = $this->reviewer->first_name . ' ' . $this->reviewer->last_name;

        $email = $this->subject("Invitation to Review Manuscript: {$this->paper->judul}")
                    ->view('emails.reviewer_assignment')
                    ->with([
                        'names' => $names,
                        'paper' => $this->paper,
                        'deadline' => $this->deadline,
                        'articleUrl' => $this->articleUrl,
                        'editorName' => $this->editorName,
                        'articleTitle' => $this->articleTitle,
                    ]);

        // Attach file jika ada
        if ($this->paper->file_path && file_exists(storage_path('app/public/' . $this->paper->file_path))) {
            $email->attach(storage_path('app/public/' . $this->paper->file_path));
        }

        return $email;
    }


}