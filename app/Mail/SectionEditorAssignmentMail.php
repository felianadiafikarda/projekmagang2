<?php

namespace App\Mail;

use App\Models\Paper;
use App\Models\User;
use Illuminate\Mail\Mailable;

class SectionEditorAssignmentMail extends Mailable
{
    public $paper;
    public $editor;
    public $editorName;
    public $subjectText;
    public $emailBody;
    public $paperFile;

    public function __construct(
        Paper $paper,
        User $editor,
        string $editorName,
        string $subjectText,
        string $emailBody
    ) {
        $this->paper       = $paper;
        $this->editor      = $editor;
        $this->editorName  = $editorName;
        $this->subjectText = $subjectText;
        $this->emailBody   = $emailBody;

        $this->paperFile = $paper->file_path
            ? storage_path('app/public/' . $paper->file_path)
            : null;
    }

    public function build()
    {
        $email = $this->from(
            config('mail.from.address'),
            'Assign Section Editor'
            )
            ->subject($this->subjectText)
            ->view('emails.section_editor_assignment')
            ->with([
                'paper'      => $this->paper,
                'editor'     => $this->editor,
                'emailBody'  => $this->emailBody,
                'editorName' => $this->editorName,
            ]);

        if ($this->paperFile && file_exists($this->paperFile)) {
            $email->attach($this->paperFile, [
                'as' => $this->paper->judul . '.pdf',
            ]);
        }

        return $email;
    }
}