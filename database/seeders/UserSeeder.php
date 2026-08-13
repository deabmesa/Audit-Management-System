<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@audit.local'],
            ['name' => 'Administrator', 'password' => Hash::make('ChangeMe123!')]
        );

        $admin->assignRole('Administrator');
    }
}
