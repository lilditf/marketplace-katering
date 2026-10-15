<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider utama aplikasi. Saat ini satu-satunya kustomisasi:
 * pagination dirender dengan markup Bootstrap 5 (default Laravel = Tailwind).
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registrasi service container (kosong, tidak ada binding custom).
     */
    public function register(): void
    {
        //
    }

    /**
     * Pengaturan yang berjalan setiap aplikasi boot.
     */
    public function boot(): void
    {
        // Pagination dirender dengan markup Bootstrap 5.
        Paginator::useBootstrapFive();

        // Di balik reverse proxy HTTPS (Railway dsb): paksa skema https agar
        // route()/asset() tidak menghasilkan URL http (form akan diblokir
        // Chrome sebagai "Form is not secure" jika action-nya http).
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
