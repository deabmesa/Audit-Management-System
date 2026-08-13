<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = ['finding_id', 'owner_id', 'recommendation', 'status', 'target_date'];

    protected $casts = ['target_date' => 'date'];

    public function finding()
    {
        return $this->belongsTo(AuditFinding::class, 'finding_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
