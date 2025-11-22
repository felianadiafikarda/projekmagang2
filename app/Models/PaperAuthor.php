<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperAuthor extends Model
{
    protected $fillable = [
        'paper_id',
        'is_primary',
        'email',
        'first_name',
        'last_name',
        'organization',
        'country',
    ];

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

}