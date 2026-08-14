<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finding extends Model
{
    protected $fillable = [
        'audit_id', 'title', 'risk_level', 'observation', 'impact', 'root_cause', 'recommendation_text', 'status'
    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}
