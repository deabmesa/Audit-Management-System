<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workpaper extends Model
{
    protected $fillable = ['audit_id', 'procedure', 'evidence_path', 'status', 'prepared_by'];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
}
