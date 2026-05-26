<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {

            try {

                // ? Prevent crash if table not ready
                if (!Schema::hasTable('menus')) {
                    $view->with('menus', collect());
                    return;
                }

                // ? Per-user cache key (RBAC safe)
                $cacheKey = 'menus.tree.' . (Auth::id() ?? 'guest');

                // ? Cache menu tree
                $menus = Cache::remember($cacheKey, now()->addMinutes(60), function () {

                    return Menu::with([
                            'childrenRecursive',
                            'children.children.children.children.children',
                        ])
                        ->whereNull('parent_id')
                        ->orderBy('order_no')
                        ->get();
                });

                // ? Apply RBAC filter (SAFE VERSION)
                if (Auth::check()) {
                    $menus = $this->filterMenus($menus);
                }

            } catch (\Throwable $e) {
                $menus = collect(); // never break UI
            }

            $view->with('menus', $menus);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SAFE RBAC FILTER (KEEP SUBMENU)
    |--------------------------------------------------------------------------
    */
    private function filterMenus($menus)
    {
        return $menus->map(function ($menu) {

            // ?? Filter children first
            if ($menu->childrenRecursive) {
                $menu->setRelation(
                    'childrenRecursive',
                    $this->filterMenus($menu->childrenRecursive)
                );
            }

            // ? Admin always allowed
            if (Auth::user()->hasRole('Admin')) {
                return $menu;
            }

            // ? No permission = allow
            if (!$menu->permission) {
                return $menu;
            }

            // ? Check permission
            $hasPermission = Auth::user()->hasPermission($menu->permission);

            // ? KEEP MENU IF:
            // - has permission
            // - OR has visible children
            if ($hasPermission || $menu->childrenRecursive->count()) {
                return $menu;
            }

            return null;

        })->filter()->values();
    }
}