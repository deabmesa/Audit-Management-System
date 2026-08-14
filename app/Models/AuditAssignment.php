<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditAssignment extends Model
{
    protected $fillable = ['audit_id', 'user_id', 'assigned_by', 'assigned_at'];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
