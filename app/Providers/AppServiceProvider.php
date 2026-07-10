<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::if('permission', function (string $slug) {
            return auth()->user()?->hasPermission($slug) ?? false;
        });

        Blade::if('role', function (string $slug) {
            return auth()->user()?->rolPrincipal?->slug === $slug;
        });
    }
}
