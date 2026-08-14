<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind interfaces to implementations here for clean architecture growth.
    }

    public function boot(): void
    {
        // Enterprise bootstrapping hooks.
    }
}
