<?php

namespace App\Services;

use App\Models\Menu;

class MenuService
{
    public function tree()
    {
        return Menu::whereNull('parent_id')->with('children')->orderBy('sort_order')->get();
    }

    public function saveTree(array $tree, ?int $parent = null): void
    {
        foreach ($tree as $order => $node) {
            $menu = Menu::updateOrCreate(['id' => $node['id'] ?? null], [
                'title' => $node['title'],
                'route' => $node['route'] ?? '#',
                'icon' => $node['icon'] ?? 'bi-circle',
                'permission' => $node['permission'] ?? null,
                'parent_id' => $parent,
                'sort_order' => $order,
            ]);
            if (! empty($node['children'])) {
                $this->saveTree($node['children'], $menu->id);
            }
        }
    }
}
