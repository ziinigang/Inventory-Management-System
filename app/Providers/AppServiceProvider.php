<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Use Bootstrap 5 pagination styling everywhere
        Paginator::useBootstrapFive();
    }
}