<?php

namespace App\Mail;

use App\Models\Paper;
use App\Models\User;
use Illuminate\Mail\Mailable;

class SectionEditorAssignmentMail extends Mailable
{
    public $paper;
    public $editor;      // section editor (penerima)
    public $editorName;  // editor pengirim
    public $subjectText;
    public $emailBody;

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
    }

    public function build()
    {
        return $this->subject($this->subjectText)
            ->view('emails.section_editor_assignment')
            ->with([
                'paper'      => $this->paper,
                'editor'     => $this->editor,
                'emailBody'  => $this->emailBody,
                'editorName' => $this->editorName,
            ]);
    }
}