<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    protected $fillable = ['name', 'step_order', 'required_role', 'final_state'];
}
