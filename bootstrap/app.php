<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

if (getenv('VERCEL')) {
    $storagePath = '/tmp/storage';
    $app->useStoragePath($storagePath);

    // Clear stale config cache if it exists in the writable path
    $configCache = $storagePath . '/framework/cache/config.php';
    if (file_exists($configCache)) {
        @unlink($configCache);
    }

    if (!is_dir($storagePath)) {
        mkdir($storagePath, 0777, true);
        mkdir($storagePath . '/framework/views', 0777, true);
        mkdir($storagePath . '/framework/cache', 0777, true);
        mkdir($storagePath . '/framework/sessions', 0777, true);
        mkdir($storagePath . '/logs', 0777, true);
    }
}

return $app;
