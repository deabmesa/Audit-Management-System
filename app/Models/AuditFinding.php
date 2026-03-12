<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditFinding extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_engagement_id', 'title', 'risk_rating', 'root_cause', 'recommendation', 'management_response',
    ];

    public function engagement()
    {
        return $this->belongsTo(AuditEngagement::class, 'audit_engagement_id');
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class, 'audit_finding_id');
    }
}
