<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// ⬅️ Tambahkan import middleware custom di sini
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\AdminPanel;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ⬅️ REGISTER alias middleware router di Laravel 11
        $middleware->alias([
            'isadmin' => IsAdmin::class,
            'admin_panel' => AdminPanel::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
