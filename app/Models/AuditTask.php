<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditTask extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = ['audit_id', 'assigned_to', 'title', 'description', 'status', 'due_date'];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
