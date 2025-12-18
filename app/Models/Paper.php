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
    ];

    public function authors()
    {
        return $this->hasMany(PaperAuthor::class);
    }

    public function reviewers()
    {
        return $this->belongsToMany(User::class, 'paper_reviewer')->withPivot('deadline', 'status', 'recommendation')->withTimestamps();
    }

    public function sectionEditors()
    {
        return $this->belongsToMany(User::class, 'paper_section_editor')->withTimestamps();
    }

    public function getEditorStatusAttribute()
{
    $this->loadMissing(['reviewers', 'sectionEditors']);

    if ($this->reviewers->isEmpty() && $this->sectionEditors->isEmpty()) {
        return 'Unassign';
    }

    if ($this->reviewers->isNotEmpty() &&
        !$this->reviewers->contains(fn($r) =>
            in_array($r->pivot->status, ['accept_to_review', 'decline_to_review'])
        )
    ) {
        return 'Awaiting Responses from Reviewers';
    }

    if ($this->reviewers->contains(fn($r) => $r->pivot->status === 'accept_to_review')) {
        return 'In Review';
    }

    if ($this->reviewers->contains(fn($r) =>
        $r->pivot->status === 'completed' &&
        $r->pivot->recommendation === 'revision'
    )) {
        return 'Accept with Review';
    }

    return '-';
}





}