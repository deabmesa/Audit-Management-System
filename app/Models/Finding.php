<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finding extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'severity', 'status', 'current_step_order', 'created_by',
    ];

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    public function evidence()
    {
        return $this->hasMany(Evidence::class);
    }
}
