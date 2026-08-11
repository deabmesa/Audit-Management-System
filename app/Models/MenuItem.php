<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'route_name',
        'url',
        'roles',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'roles' => 'array',
        'is_active' => 'boolean',
    ];

    public function isVisibleForRole(?string $role): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (empty($this->roles)) {
            return true;
        }

        return in_array($role, $this->roles, true);
    }
}
