<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasUuids;

    protected $fillable = ['audit_id', 'title', 'status', 'assigned_to', 'due_date'];
    protected $casts = ['due_date' => 'date'];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
}
