<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Ensure storage link works for uploaded images
        \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
    }
}
