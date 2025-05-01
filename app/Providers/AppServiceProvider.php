<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register middleware aliases
        $router = app('router');
        $router->aliasMiddleware('prevent-user-editing', \App\Http\Middleware\PreventUserEditing::class);
        $router->aliasMiddleware('restrict.user.editing', \App\Http\Middleware\PreventUserEditing::class);
        $router->aliasMiddleware('checkRole', \App\Http\Middleware\CheckRole::class);
    }
}
