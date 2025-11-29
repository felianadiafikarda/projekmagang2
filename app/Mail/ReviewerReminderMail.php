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

    public function __construct(Paper $paper, User $reviewer, $editorName)
    {
        $this->paper = $paper;
        $this->reviewer = $reviewer;
        $this->editorName = $editorName;

        $this->reviewerName = trim(($reviewer->first_name ?? '') . ' ' . ($reviewer->last_name ?? $reviewer->name ?? ''));

        $this->articleTitle = $paper->judul ?? $paper->title ?? 'Untitled';

        // Ambil deadline dari pivot jika ada (cek relasi pivot)
        $deadline = null;
        // hanya jika relasi reviewers() ada dan pivot tersedia
        try {
            $pivotRow = $paper->reviewers()->where('users.id', $reviewer->id)->first();
            if ($pivotRow && isset($pivotRow->pivot->deadline)) {
                $deadline = $pivotRow->pivot->deadline;
            }
        } catch (\Throwable $e) {
            $deadline = null;
        }
        $this->deadline = $deadline ?? 'No deadline specified';

        $this->articleUrl = $paper->file_path ? asset('storage/' . $paper->file_path) : '#';
    }

    public function build()
    {
        $names = $this->reviewer->first_name . ' ' . $this->reviewer->last_name;

        $email = $this->subject("Just a gentle reminder regarding the manuscript {$this->paper->judul}")
                    ->view('emails.reviewer_reminder')
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