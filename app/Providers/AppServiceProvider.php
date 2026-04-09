<?php

namespace App\Providers;

use App\Models\MenuItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            if (!Auth::check()) {
                $view->with('dynamicMenuItems', collect());
                return;
            }

            $role = Auth::user()->role;
            $menuItems = MenuItem::orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->filter(function (MenuItem $item) use ($role) {
                    if (!$item->isVisibleForRole($role)) {
                        return false;
                    }

                    if ($item->route_name) {
                        return Route::has($item->route_name);
                    }

                    return filled($item->url);
                })
                ->values();

            $view->with('dynamicMenuItems', $menuItems);
        });
    }
}
