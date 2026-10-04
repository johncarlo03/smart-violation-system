<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

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
        if ($this->app->environment('production') || request()->hasHeader('x-forwarded-proto')) {
            URL::forceScheme('https');
        }

        RedirectIfAuthenticated::redirectUsing(function() {
            $user = Auth::user();

            return match($user?->role){
                3 => route('sao.dashboard'),
                2 => route('cso.dashboard'),
                default => route('dashboard'),
            };
        });
    }
}
