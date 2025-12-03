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
        return $this->belongsToMany(User::class, 'paper_reviewer')->withPivot('deadline', 'status', 'recommendation', 'invitation_token')->withTimestamps();
    }

    public function sectionEditors()
    {
        return $this->belongsToMany(User::class, 'paper_section_editor')->withTimestamps();
    }


}