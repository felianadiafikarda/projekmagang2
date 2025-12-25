<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreparedEmail extends Model
{
    protected $table = 'prepared_email';

    protected $fillable = [
        'email_template',
        'sender',
        'recipient',
        'subject',
        'body',
    ];

    protected $casts = [
        'sender'    => 'array',
        'recipient' => 'array',
    ];
}
