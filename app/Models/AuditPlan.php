<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditPlan extends Model
{
    protected $fillable = ['audit_id', 'objectives', 'scope', 'risk_assessment', 'control_areas'];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
}
