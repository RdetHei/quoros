<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'not_banned' => \App\Http\Middleware\EnsureNotBanned::class,
        ]);

        // Selama maintenance, area admin tetap dapat diakses agar administrator
        // dapat memantau dan menonaktifkan mode pemeliharaan kembali.
        $middleware->preventRequestsDuringMaintenance(except: [
            'admin',
            'admin/*',
            'login',
            'logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
