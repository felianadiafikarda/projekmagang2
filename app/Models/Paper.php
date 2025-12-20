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

    public function getDisplayStatusAttribute()
{
    // 1. Kalau editor sudah memutuskan
    if ($this->editor_status) {
        return $this->editor_status;
    }

    $reviewers = $this->reviewers;

    if ($reviewers->isEmpty()) {
        return 'Unassigned';
    }

    // Hitung status reviewer
    $statuses = $reviewers->pluck('pivot.status');

    // 2. Jika ADA reviewer yang accept / assigned
    if ($statuses->contains(fn ($s) =>
        in_array($s, ['assigned', 'accept_to_review'])
    )) {
        return 'In Review';
    }

    // 3. Jika SEMUA reviewer decline
    if ($statuses->every(fn ($s) => $s === 'decline_to_review')) {
        return 'Decline to Review';
    }

    // 4. Jika SEMUA reviewer completed
    if ($statuses->every(fn ($s) => $s === 'completed')) {
        return 'Completed Review';
    }

    return 'Pending';
}






}