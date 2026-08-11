<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasUuids;

    protected $fillable = ['user_id', 'action', 'payload'];
    protected $casts = ['payload' => 'array'];

    public static function record(string $action, ?string $userId = null, array $payload = []): self
    {
        return self::create(['user_id' => $userId, 'action' => $action, 'payload' => $payload]);
    }
}
