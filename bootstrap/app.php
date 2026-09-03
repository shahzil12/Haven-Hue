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

// Robust Vercel Environment Check
$isVercel = isset($_ENV['VERCEL']) || getenv('VERCEL') || isset($_SERVER['VERCEL']);

if ($isVercel) {
    $storagePath = '/tmp/storage';
    $bootstrapPath = '/tmp/bootstrap';

    $app->useStoragePath($storagePath);
    $app->useBootstrapPath($bootstrapPath);

    // Create necessary directories
    if (!is_dir($storagePath)) {
        mkdir($storagePath, 0777, true);
        mkdir($storagePath . '/framework/views', 0777, true);
        mkdir($storagePath . '/framework/cache', 0777, true);
        mkdir($storagePath . '/framework/sessions', 0777, true);
        mkdir($storagePath . '/logs', 0777, true);
    }

    if (!is_dir($bootstrapPath)) {
        mkdir($bootstrapPath, 0777, true);
        mkdir($bootstrapPath . '/cache', 0777, true);
    }
    
    // Clear stale cache files in /tmp to prevent collisions
    $filesToClear = [
        $bootstrapPath . '/cache/config.php',
        $bootstrapPath . '/cache/services.php',
        $bootstrapPath . '/cache/packages.php',
        $storagePath . '/framework/routes.php'
    ];
    foreach ($filesToClear as $file) {
        if (file_exists($file)) {
            @unlink($file);
        }
    }

    $dbConn = env('DB_CONNECTION', 'sqlite');
    $hasPostgres = ($dbConn === 'pgsql') || env('DB_URL') || env('DATABASE_URL') || env('POSTGRES_URL');

    // Only fallback to ephemeral SQLite if PostgreSQL is NOT configured
    if (!$hasPostgres) {
        $dbPath = $storagePath . '/database.sqlite';
        $sourceDb = $app->basePath('database/database.sqlite');
        
        $needSeeding = false;
        if (!file_exists($dbPath)) {
            if (file_exists($sourceDb) && filesize($sourceDb) > 0) {
                copy($sourceDb, $dbPath);
            } else {
                touch($dbPath);
                $needSeeding = true;
            }
        }
        
        $_ENV['DB_DATABASE'] = $dbPath;
        putenv("DB_DATABASE={$dbPath}");

        if ($needSeeding) {
            try {
                $console = new \Symfony\Component\Console\Output\BufferedOutput();
                $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
                $kernel->call('migrate', ['--force' => true], $console);
                $kernel->call('db:seed', ['--force' => true], $console);
            } catch (\Throwable $e) {
                // Silence migration/seeding exceptions on initial boot
            }
        }
    }

    $_ENV['SESSION_DRIVER'] = env('SESSION_DRIVER', 'cookie');
    putenv('SESSION_DRIVER=' . env('SESSION_DRIVER', 'cookie'));

    $_ENV['CACHE_STORE'] = env('CACHE_STORE', 'array');
    putenv('CACHE_STORE=' . env('CACHE_STORE', 'array'));
}

return $app;
