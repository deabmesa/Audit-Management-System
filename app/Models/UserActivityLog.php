<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
