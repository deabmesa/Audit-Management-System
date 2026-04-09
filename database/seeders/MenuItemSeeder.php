<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['title' => 'Dashboard', 'route_name' => 'dashboard', 'url' => null, 'roles' => null],
            ['title' => 'Audits', 'route_name' => 'audits.index', 'url' => null, 'roles' => [User::ROLE_ADMIN, User::ROLE_AUDITOR, User::ROLE_REVIEWER]],
            ['title' => 'Users', 'route_name' => 'users.index', 'url' => null, 'roles' => [User::ROLE_ADMIN]],
            ['title' => 'Activity Logs', 'route_name' => 'users.logs', 'url' => null, 'roles' => [User::ROLE_ADMIN]],
            ['title' => 'Menu Builder', 'route_name' => 'admin.menu.index', 'url' => null, 'roles' => [User::ROLE_ADMIN]],
        ];

        foreach ($items as $index => $item) {
            MenuItem::updateOrCreate(
                ['title' => $item['title']],
                [
                    'route_name' => $item['route_name'],
                    'url' => $item['url'],
                    'roles' => $item['roles'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
