<?php

use App\Http\Middleware\AuthCheck;
use App\Http\Middleware\EnsureApplicationNotInCustomMaintenanceMode;
use App\Http\Middleware\SaranaUmumAccessMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            EnsureApplicationNotInCustomMaintenanceMode::class,
        ]);

        $middleware->alias([
            'role' => AuthCheck::class,
            'sarana-umum.access' => SaranaUmumAccessMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
