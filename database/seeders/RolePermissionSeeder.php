<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view dashboard',
            'manage audits',
            'manage issues',
            'manage attachments',
            'access api',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $auditor = Role::firstOrCreate(['name' => 'Auditor', 'guard_name' => 'web']);

        $admin->syncPermissions($permissions);
        $manager->syncPermissions(['view dashboard', 'manage audits', 'manage issues', 'manage attachments']);
        $auditor->syncPermissions(['view dashboard', 'manage audits', 'manage issues']);
    }
}
