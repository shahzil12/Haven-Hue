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
        $exceptions->render(function (Throwable $e) {
            if (getenv('VERCEL')) {
                // Bypass View rendering to avoid "Target class [view] does not exist"
                // during error handling.
                echo "<h1>Primary Exception (Raw Output)</h1>";
                echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
                echo "<pre>" . $e->getTraceAsString() . "</pre>";
                exit; // Stop execution
            }
        });
    })->create();

if (getenv('VERCEL')) {
    $storagePath = '/tmp/storage';
    $app->useStoragePath($storagePath);

    // Clear stale config cache if it exists in the writable path
    $filesToClear = [
        $storagePath . '/framework/cache/config.php',
        $storagePath . '/framework/cache/services.php',
        $storagePath . '/framework/cache/packages.php',
        $storagePath . '/framework/routes.php'
    ];
    foreach ($filesToClear as $file) {
        if (file_exists($file)) {
            @unlink($file);
        }
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
