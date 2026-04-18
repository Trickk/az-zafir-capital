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
        $middleware->use([
            \Illuminate\Http\Middleware\HandleCors::class,  // ← añadir esta línea
        ]);
        $middleware->alias([
            'fivem.token' => \App\Http\Middleware\FivemTokenAuth::class,
        ]);

        $middleware->redirectUsersTo('/admin'); # or '/dashboard'
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
