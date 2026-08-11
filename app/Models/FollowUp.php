<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_finding_id', 'recommendation_status', 'due_date', 'follow_up_comments', 'status',
    ];

    public function finding()
    {
        return $this->belongsTo(AuditFinding::class, 'audit_finding_id');
    }
}
