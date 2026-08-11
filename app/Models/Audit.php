<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Audit extends Model
{
    use HasUuids;

    protected $fillable = ['title', 'module', 'status', 'starts_at', 'ends_at'];
    protected $casts = ['starts_at' => 'date', 'ends_at' => 'date'];

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }
}
