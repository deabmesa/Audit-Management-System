<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvidenceVersion extends Model
{
    protected $fillable = ['evidence_id', 'version', 'file_path', 'checksum_sha256', 'uploaded_by'];
}
