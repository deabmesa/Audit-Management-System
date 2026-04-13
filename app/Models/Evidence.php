<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    protected $fillable = ['finding_id', 'name'];

    public function versions()
    {
        return $this->hasMany(EvidenceVersion::class);
    }
}
