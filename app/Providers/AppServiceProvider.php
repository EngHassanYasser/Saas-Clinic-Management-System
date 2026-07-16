<?php

namespace App\Providers;

use App\Models\complain;
use App\Observers\ComplainObserver;
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
          complain::observe(ComplainObserver::class);
    }
}
