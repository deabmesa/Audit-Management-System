<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@audit.local'],
            ['name' => 'System Admin', 'password' => Hash::make('password123'), 'role' => 'Admin']
        );

        User::updateOrCreate(
            ['email' => 'auditor@audit.local'],
            ['name' => 'Lead Auditor', 'password' => Hash::make('password123'), 'role' => 'Auditor']
        );

        User::updateOrCreate(
            ['email' => 'reviewer@audit.local'],
            ['name' => 'Audit Reviewer', 'password' => Hash::make('password123'), 'role' => 'Reviewer']
        );
    }
}
