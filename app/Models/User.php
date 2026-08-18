<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'Admin';
    public const ROLE_AUDITOR = 'Auditor';
    public const ROLE_REVIEWER = 'Reviewer';

    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function activityLogs()
    {
        return $this->hasMany(UserActivityLog::class);
    }

    public function assignedAudits()
    {
        return $this->belongsToMany(AuditEngagement::class, 'audit_assignments');
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
}
