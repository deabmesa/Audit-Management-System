<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditFinding extends Model
{
    protected $fillable = [
        'audit_program_id',
        'title',
        'risk_level',
        'recommendation',
        'status',
    ];
}
