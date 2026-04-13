<?php

namespace Database\Seeders;

use App\Models\FieldPermission;
use App\Models\Finding;
use App\Models\Menu;
use App\Models\User;
use App\Models\WorkflowStep;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'findings.view', 'findings.create', 'findings.update', 'findings.delete',
            'workflow.approve', 'workflow.design', 'roles.manage', 'menus.manage', 'reports.generate',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $auditorRole = Role::firstOrCreate(['name' => 'Auditor', 'guard_name' => 'web']);
        $reviewerRole = Role::firstOrCreate(['name' => 'Reviewer', 'guard_name' => 'web']);

        $adminRole->syncPermissions($permissions);
        $auditorRole->syncPermissions(['findings.view', 'findings.create', 'findings.update', 'reports.generate']);
        $reviewerRole->syncPermissions(['findings.view', 'workflow.approve', 'reports.generate']);

        $admin = User::firstOrCreate(['email' => 'admin@test.com'], ['name' => 'Admin User', 'password' => Hash::make('123456')]);
        $auditor = User::firstOrCreate(['email' => 'auditor@test.com'], ['name' => 'Auditor User', 'password' => Hash::make('123456')]);
        $reviewer = User::firstOrCreate(['email' => 'reviewer@test.com'], ['name' => 'Reviewer User', 'password' => Hash::make('123456')]);

        $admin->assignRole($adminRole);
        $auditor->assignRole($auditorRole);
        $reviewer->assignRole($reviewerRole);

        $titles = [
            'Weak password policy', 'Missing access control logs', 'Unencrypted backup storage', 'Inactive user accounts not disabled',
            'Firewall rules not reviewed', 'No MFA for privileged users', 'Insecure API endpoint', 'Excessive DB privileges',
            'Lack of disaster recovery drills', 'Unpatched operating systems', 'No data retention policy', 'Change management gaps',
            'No segregation of duties', 'Legacy TLS protocols enabled', 'No SIEM alert tuning', 'Vendor risk review overdue',
            'PII masking missing', 'Orphaned service accounts', 'Endpoint encryption disabled', 'Backup restore tests missing'
        ];

        foreach ($titles as $idx => $title) {
            Finding::create([
                'title' => $title,
                'description' => $title.' identified during quarterly internal audit cycle.',
                'severity' => ['low', 'medium', 'high', 'critical'][$idx % 4],
                'status' => ['open', 'review', 'approved', 'closed'][$idx % 4],
                'current_step_order' => 1,
                'created_by' => $admin->id,
            ]);
        }

        WorkflowStep::insert([
            ['name' => 'Open', 'step_order' => 1, 'required_role' => 'Auditor', 'final_state' => 'open', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Review', 'step_order' => 2, 'required_role' => 'Reviewer', 'final_state' => 'review', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Approved', 'step_order' => 3, 'required_role' => 'Admin', 'final_state' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Closed', 'step_order' => 4, 'required_role' => 'Admin', 'final_state' => 'closed', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Menu::insert([
            ['title' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'bi-speedometer2', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Findings', 'route' => 'findings.index', 'icon' => 'bi-search', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Reports', 'route' => 'activity-logs.index', 'icon' => 'bi-file-earmark-bar-graph', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Admin', 'route' => 'roles.index', 'icon' => 'bi-shield-lock', 'sort_order' => 4, 'permission' => 'roles.manage', 'created_at' => now(), 'updated_at' => now()],
        ]);

        FieldPermission::create(['model' => 'findings', 'field' => 'status', 'role_name' => 'Admin', 'can_view' => true, 'can_edit' => true]);
        FieldPermission::create(['model' => 'findings', 'field' => 'status', 'role_name' => 'Auditor', 'can_view' => true, 'can_edit' => false]);
        FieldPermission::create(['model' => 'findings', 'field' => 'status', 'role_name' => 'Reviewer', 'can_view' => true, 'can_edit' => false]);
    }
}
