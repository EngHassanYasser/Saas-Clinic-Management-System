<?php

namespace App\Providers;

use App\Models\complaint;
use App\Observers\ComplaintObserver;
use App\Support\TenantContext;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TenantContext::class, function () {
            return new TenantContext;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    RateLimiter::for('login', function (Request $request) {
        return Limit::perMinute(5)
            ->by($request->ip());
    });

    RateLimiter::for('register', function (Request $request) {
        return Limit::perMinute(5)
            ->by($request->ip());
    });

    RateLimiter::for('password-reset', function (Request $request) {
        return Limit::perMinute(3)
            ->by($request->ip());
    });
        complaint::observe(ComplaintObserver::class);
    }
}
