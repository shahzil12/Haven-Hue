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
            if (!file_exists($dbPath)) {
                touch($dbPath);
                \Illuminate\Support\Facades\Artisan::call('migrate --force');
            }
        }
    }
}
