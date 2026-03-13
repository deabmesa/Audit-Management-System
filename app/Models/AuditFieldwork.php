<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditFieldwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_engagement_id', 'checklist_item', 'evidence_path', 'working_paper', 'audit_notes', 'status',
    ];

    public function engagement()
    {
        return $this->belongsTo(AuditEngagement::class, 'audit_engagement_id');
    }
}
