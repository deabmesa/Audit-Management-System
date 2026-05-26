<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password','username','phone','branch','is_active'
    ];

    protected $hidden = [
        'password','remember_token'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function roles()
    {
        // ?? IMPORTANT: specify pivot table
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE CHECK
    |--------------------------------------------------------------------------
    */

    public function hasRole(string $role): bool
    {
        return $this->roles->pluck('name')->contains($role);
    }

    /*
    |--------------------------------------------------------------------------
    | PERMISSION CHECK
    |--------------------------------------------------------------------------
    */

    public function hasPermission(string $permission): bool
    {
        // Admin override
        if ($this->hasRole('Admin')) {
            return true;
        }

        return $this->roles
            ->loadMissing('permissions')
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->contains($permission);
    }
}