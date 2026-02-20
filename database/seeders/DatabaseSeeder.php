<?php

namespace Database\Seeders;

use App\Models\Audit;
use App\Models\Issue;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'Admin', 'description' => 'System Administrator']);
        $auditorRole = Role::create(['name' => 'Auditor', 'description' => 'Audit performer']);
        $managerRole = Role::create(['name' => 'Manager', 'description' => 'Department manager']);

        $admin = User::create([
            'role_id' => $adminRole->id,
            'name' => 'System Admin',
            'staff_id' => 'ADM-1001',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'photo_path' => 'images/profiles/admin.png',
            'password' => Hash::make('password123'),
        ]);

        $auditor = User::create([
            'role_id' => $auditorRole->id,
            'name' => 'Lead Auditor',
            'staff_id' => 'AUD-2001',
            'username' => 'auditor',
            'email' => 'auditor@example.com',
            'password' => Hash::make('password123'),
        ]);

        $manager = User::create([
            'role_id' => $managerRole->id,
            'name' => 'Operations Manager',
            'staff_id' => 'MGR-3001',
            'username' => 'manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('password123'),
        ]);

        $audit = Audit::create([
            'title' => 'Q1 Compliance Audit',
            'module' => 'Pre-Audit',
            'status' => 'In Progress',
            'starts_at' => now()->subDays(5),
            'ends_at' => now()->addDays(20),
        ]);

        Task::create([
            'audit_id' => $audit->id,
            'title' => 'Prepare pre-audit checklist',
            'status' => 'Open',
            'assigned_to' => $auditor->id,
            'due_date' => now()->addDays(5),
        ]);

        Issue::create([
            'audit_id' => $audit->id,
            'title' => 'Missing policy approval signatures',
            'description' => 'Two policies were missing final sign-off.',
            'status' => 'Open',
            'responsible_user_id' => $manager->id,
            'due_date' => now()->addDays(14),
        ]);
    }
}
