<?php

namespace App\Services;

use App\Models\Finding;

class AuditHashService
{
    public function findingHash(Finding $finding): string
    {
        $payload = json_encode([
            'id' => $finding->id,
            'title' => $finding->title,
            'status' => $finding->status,
            'evidence' => $finding->evidence->map(fn ($e) => $e->versions->pluck('checksum_sha256')),
            'approvals' => $finding->approvals->map(fn ($a) => [$a->action, $a->remarks, $a->created_at]),
        ]);
        return hash('sha256', $payload ?: '');
    }
}
