<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            'view-dashboard', 'manage-users', 'manage-audits', 'manage-findings', 'manage-reports'
        ])->map(fn ($name) => Permission::firstOrCreate(['name' => $name]));

        Role::firstOrCreate(['name' => 'Administrator'])->syncPermissions($permissions);
        Role::firstOrCreate(['name' => 'Auditor'])->syncPermissions(['view-dashboard', 'manage-audits', 'manage-findings']);
        Role::firstOrCreate(['name' => 'Manager'])->syncPermissions(['view-dashboard', 'manage-reports']);
    }
}
