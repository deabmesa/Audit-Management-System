<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckInRecord extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'ip_branch',
        'real_branch',
        'reason',
        'checked_in_at',
        'checked_out_at',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];
}
