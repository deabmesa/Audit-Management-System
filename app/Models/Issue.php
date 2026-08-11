<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasUuids;

    protected $fillable = ['audit_id', 'title', 'description', 'status', 'responsible_user_id', 'due_date'];
    protected $casts = ['due_date' => 'date'];

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }
}
