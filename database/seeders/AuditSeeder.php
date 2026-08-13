<?php

namespace Database\Seeders;

use App\Models\Audit;
use App\Models\AuditFinding;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::first();

        if (! $owner) {
            return;
        }

        $audit = Audit::create([
            'title' => 'Finance Process Audit',
            'category' => 'Finance',
            'status' => 'in_progress',
            'planned_start_at' => now()->subDays(10),
            'planned_end_at' => now()->addDays(20),
            'owner_id' => $owner->id,
        ]);

        AuditFinding::create([
            'audit_id' => $audit->id,
            'title' => 'Missing approval workflow',
            'details' => 'No evidence of second-level approval for high-value transactions.',
            'severity' => 'High',
            'status' => 'open',
            'due_date' => now()->addDays(7),
        ]);
    }
}
