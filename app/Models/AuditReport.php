<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditReport extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = ['audit_id', 'type', 'content', 'status', 'published_at'];

    protected $casts = ['published_at' => 'datetime'];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
}
