<?php

namespace App\Providers;

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
        if (\Illuminate\Support\Facades\App::environment('production') || env('VERCEL')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        if (env('VERCEL')) {
            $dbPath = '/tmp/database.sqlite';
            $sourceDb = database_path('database.sqlite');

            if (!file_exists($dbPath) || filesize($dbPath) < 10240) {
                if (file_exists($sourceDb) && filesize($sourceDb) > 10240) {
                    @copy($sourceDb, $dbPath);
                } else {
                    @touch($dbPath);
                    try {
                        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                    } catch (\Throwable $e) {
                        // ignore if read-only
                    }
                }
            }
        }
    }
}
