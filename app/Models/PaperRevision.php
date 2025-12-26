<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperRevision extends Model
{
    protected $fillable = [
        'paper_id','judul','abstrak','keywords',
        'paper_references','file_path',
        'revision_notes','submitted_at'
    ];

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }
}