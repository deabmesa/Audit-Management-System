<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = ['user_id', 'action', 'entity_type', 'entity_id', 'ip_address', 'metadata'];

    protected $casts = ['metadata' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
