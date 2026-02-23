<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $fillable = [
        'title', 'audit_type', 'department', 'risk_category', 'start_date', 'end_date', 'status', 'created_by'
    ];

    public function assignments()
    {
        return $this->hasMany(AuditAssignment::class);
    }

    public function plans()
    {
        return $this->hasMany(AuditPlan::class);
    }

    public function workpapers()
    {
        return $this->hasMany(Workpaper::class);
    }

    public function findings()
    {
        return $this->hasMany(Finding::class);
    }
}
