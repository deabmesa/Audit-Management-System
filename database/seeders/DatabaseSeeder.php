<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $admin = User::firstOrCreate(
            ['email' => 'admin@audit.local'],
            ['name' => 'System Admin', 'password' => Hash::make('password')]
        );

        $admin->assignRole('Admin');
    }
}
