<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportPermission extends Model
{
    protected $fillable = [
        'report_template_id',
        'role_name',
        'user_id',
        'can_view',
    ];

    protected $casts = [
        'can_view' => 'boolean',
    ];
}
