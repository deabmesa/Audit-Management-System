<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditFinding extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = ['audit_id', 'title', 'details', 'severity', 'status', 'due_date', 'resolved_at'];

    protected $casts = ['due_date' => 'date', 'resolved_at' => 'datetime'];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function recommendation()
    {
        return $this->hasOne(Recommendation::class, 'finding_id');
    }
}
