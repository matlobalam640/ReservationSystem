<?php

namespace App\Providers;

use App\Auth\Http\Responses\LogoutResponse;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole() && ($rootUrl = config('app.url'))) {
            \Illuminate\Support\Facades\URL::forceRootUrl($rootUrl);
        }

        \Illuminate\Support\Facades\Gate::policy(\App\Models\Booking::class, \App\Policies\BookingPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Invoice::class, \App\Policies\InvoicePolicy::class);
    }
}
