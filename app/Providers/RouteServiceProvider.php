<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Where to redirect after login.
     */
    public const HOME = '/home';

    /**
     * Boot the route service provider.
     */
    public function boot(): void
    {
        $this->routes(function () {

            // ROUTES API
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // ROUTES WEB
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
