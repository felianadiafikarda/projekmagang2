<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PaperAuthor extends Model
{
    protected $fillable = [
        'paper_id',
        'is_primary',
        'email',
        'first_name',
        'last_name',
        'orcid',
        'organization',
        'country',
    ];

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
    
}