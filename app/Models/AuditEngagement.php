<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditEngagement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'scope', 'risk_level', 'planned_start_date', 'planned_end_date', 'status', 'created_by',
    ];

    public function auditors()
    {
        return $this->belongsToMany(User::class, 'audit_assignments');
    }

    public function fieldworkItems()
    {
        return $this->hasMany(AuditFieldwork::class, 'audit_engagement_id');
    }

    public function findings()
    {
        return $this->hasMany(AuditFinding::class, 'audit_engagement_id');
    }
}
