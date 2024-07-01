<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
     * Cấu hình sử dụng bootstrap
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}