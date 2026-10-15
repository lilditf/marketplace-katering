<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider utama aplikasi. Saat ini satu-satunya kustomisasi:
 * pagination dirender dengan markup Bootstrap 5 (default Laravel = Tailwind).
 */
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
        // Render pagination dengan markup Bootstrap 5 (default Laravel = Tailwind).
        Paginator::useBootstrapFive();
    }
}
