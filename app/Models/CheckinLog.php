<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckinLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'ip_address',
        'machine_name',
        'checked_in_at',
        'checked_out_at',
        'session_id',
        'is_active',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'is_active' => 'bool',
    ];
}
