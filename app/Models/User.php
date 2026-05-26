<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password','username','phone','branch','is_active','role'
    ];

    protected $hidden = [
        'password','remember_token'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | BOOT - DEFAULT ROLE
    |--------------------------------------------------------------------------
    */
    
    protected static function booted()
    {
        static::creating(function ($user) {
            if (!$user->role) {
                $user->role = 'user';
            }
        });
    }

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

    /**
     * ✅ FIX: User has many activity logs
     */
    public function activityLogs()
    {
        return $this->hasMany(UserActivityLog::class);
    }

    /**
     * ✅ FIX: User has many attendance records
     */
    public function attendances()
    {
        return $this->hasMany(Checkin::class);
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

    public function isAdmin(): bool
    {
        return $this->role === 'Admin' || $this->hasRole('Admin');
    }

    public function isUser(): bool
    {
        return $this->role === 'user' || $this->hasRole('user');
    }

    /*
    |--------------------------------------------------------------------------
    | PERMISSION CHECK
    |--------------------------------------------------------------------------
    */

    public function hasPermission(string $permission): bool
    {
        // Admin override
        if ($this->isAdmin()) {
            return true;
        }

        return $this->roles
            ->loadMissing('permissions')
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->contains($permission);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }
}
