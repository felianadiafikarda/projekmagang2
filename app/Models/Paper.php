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
    ];

    public function authors()
    {
        return $this->hasMany(PaperAuthor::class);
    }
}