<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditProgram extends Model
{
    protected $fillable = [
        'title',
        'scope',
        'start_date',
        'end_date',
        'owner_id',
        'status',
    ];
}
