<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldPermission extends Model
{
    protected $fillable = ['model', 'field', 'role_name', 'can_view', 'can_edit'];
}
