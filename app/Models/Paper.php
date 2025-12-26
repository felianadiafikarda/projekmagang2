<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'abstrak',
        'keywords',
        'file_path',
        'status',
        'editor_status',
        'paper_references',
    ];

    public function authors()
    {
        return $this->hasMany(PaperAuthor::class);
    }

    public function reviewers()
    {
        return $this->belongsToMany(User::class, 'paper_reviewer')
            ->withPivot([
                'deadline',
                'status',
                'recommendation',
                'Q1',
                'Q2',
                'Q3',
                'comments_for_author',
                'comments_for_editor',
                'review_file',
                'review_file_name',
                'reviewed_at',
                
            ])
            ->withTimestamps();
    }


    public function sectionEditors()
    {
        return $this->belongsToMany(User::class, 'paper_section_editor')->withTimestamps();
    }

    public function getEditorDisplayStatusAttribute()
{
    if ($this->editor_status === 'accepted') {
        return 'Accepted';
    }

    if ($this->editor_status === 'rejected') {
        return 'Rejected';
    }

    if ($this->editor_status === 'accept_with_review') {
        if ($this->status === 'revised') {
            return 'Revised';
        }
        return 'Accept with Review';
    }

    if ($this->reviewers->isEmpty()) {
        return 'Unassign';
    }

    return 'In Review';
}


public function getAuthorStatusAttribute()
{
    if ($this->editor_status === 'accepted') {
        return 'Accepted';
    }

    if ($this->editor_status === 'rejected') {
        return 'Rejected';
    }

    if ($this->editor_status === 'accept_with_review') {
        if ($this->status === 'revised') {
            return 'Revised';
        }
        return 'Accept with Review';
    }

    if ($this->reviewers->isNotEmpty()) {
        return 'In Review';
    }

    return match ($this->status) {
        'submitted' => 'Submitted',
        'revised'   => 'Revised',
        default     => ucfirst($this->status),
    };
}
  
    public function revisions()
    {
        return $this->hasMany(PaperRevision::class);
    }
}