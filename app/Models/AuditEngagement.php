<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditEngagement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'entity_name',
        'activity',
        'working_notes',
        'evidence_path',
        'status',
        'start_date',
        'end_date',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
