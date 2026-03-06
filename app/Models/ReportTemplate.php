<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'database_source',
        'sql_query',
        'output_columns',
        'filters',
        'is_active',
    ];

    protected $casts = [
        'output_columns' => 'array',
        'filters' => 'array',
        'is_active' => 'boolean',
    ];
}
