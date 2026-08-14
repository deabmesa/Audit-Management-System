<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $fillable = ['finding_id', 'owner', 'due_date', 'implementation_status'];

    public function finding()
    {
        return $this->belongsTo(Finding::class);
    }
}
