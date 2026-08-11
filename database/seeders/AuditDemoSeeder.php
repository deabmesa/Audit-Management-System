<?php

namespace Database\Seeders;

use App\Models\AuditEngagement;
use App\Models\AuditFieldwork;
use App\Models\AuditFinding;
use App\Models\FollowUp;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Database\Seeder;

class AuditDemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'Admin')->first();
        $auditor = User::where('role', 'Auditor')->first();

        $audit = AuditEngagement::create([
            'title' => 'FY2026 Procurement Compliance Audit',
            'scope' => 'Procurement lifecycle, vendor onboarding, and approval controls.',
            'risk_level' => 'High',
            'planned_start_date' => now()->subDays(7)->toDateString(),
            'planned_end_date' => now()->addDays(21)->toDateString(),
            'status' => 'In Progress',
            'created_by' => $admin->id,
        ]);

        $audit->auditors()->sync([$auditor->id]);

        AuditFieldwork::create([
            'audit_engagement_id' => $audit->id,
            'checklist_item' => 'Vendor due diligence checklist completed',
            'working_paper' => 'Reviewed 25 vendor files and KYC documents.',
            'audit_notes' => '2 vendors had missing conflict declarations.',
            'status' => 'Completed',
        ]);

        $finding = AuditFinding::create([
            'audit_engagement_id' => $audit->id,
            'title' => 'Incomplete vendor conflict-of-interest declaration',
            'risk_rating' => 'Medium',
            'root_cause' => 'No mandatory system validation before onboarding approval.',
            'recommendation' => 'Add blocking validation rule and annual vendor declaration refresh.',
            'management_response' => 'IT and Procurement will implement controls in Q2.',
        ]);

        FollowUp::create([
            'audit_finding_id' => $finding->id,
            'recommendation_status' => 'Implementation in progress',
            'due_date' => now()->addDays(30)->toDateString(),
            'follow_up_comments' => 'Requirement document signed off.',
            'status' => 'Open',
        ]);

        UserActivityLog::create([
            'user_id' => $admin->id,
            'activity' => 'Created audit engagement and assigned auditor',
            'ip_address' => '127.0.0.1',
        ]);
    }
}
