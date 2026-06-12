<?php

use App\Http\Middleware\ApiLoggerMiddleware;
use App\Http\Middleware\EnsureMfaIsVerified;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        api: __DIR__.'/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'mfa.verified' => EnsureMfaIsVerified::class,
            'api.logger' => ApiLoggerMiddleware::class,
        ]);
        $middleware->redirectTo('/admin/login');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
