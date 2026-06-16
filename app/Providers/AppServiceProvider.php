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
        if (! $this->app->runningInConsole()) {
            $request = request();
            $rootUrl = config('app.url');

            // Avoid broken URLs when production still has a localhost APP_URL.
            if (is_string($rootUrl) && str_contains($rootUrl, 'localhost') && $request->getHost()) {
                $rootUrl = $request->getSchemeAndHttpHost();
                config(['app.url' => $rootUrl]);
            }

            if ($rootUrl) {
                \Illuminate\Support\Facades\URL::forceRootUrl($rootUrl);
            }

            if ($request->header('X-Forwarded-Proto') === 'https' || $request->isSecure()) {
                \Illuminate\Support\Facades\URL::forceScheme('https');
                config(['session.secure' => true]);
            }
        }

        \Illuminate\Support\Facades\Gate::policy(\App\Models\Booking::class, \App\Policies\BookingPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Invoice::class, \App\Policies\InvoicePolicy::class);
    }
}
