<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditPlan extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = ['audit_id', 'planning_notes', 'risk_assessment', 'audit_program', 'document_requests'];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
}
