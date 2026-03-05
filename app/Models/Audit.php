<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = ['title', 'category', 'status', 'planned_start_at', 'planned_end_at', 'owner_id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function plan()
    {
        return $this->hasOne(AuditPlan::class);
    }

    public function tasks()
    {
        return $this->hasMany(AuditTask::class);
    }

    public function findings()
    {
        return $this->hasMany(AuditFinding::class);
    }

    public function reports()
    {
        return $this->hasMany(AuditReport::class);
    }
}
