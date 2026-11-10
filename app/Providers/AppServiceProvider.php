<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Event;
use App\Listeners\LogAuthActivity;
use Illuminate\Support\Facades\View;
use App\Models\{Course, Department};

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

        Event::subscribe(LogAuthActivity::class);

        RedirectIfAuthenticated::redirectUsing(function () {
            $user = Auth::user();

            return match ($user?->role) {
                3 => route('sao.dashboard'),
                2 => route('cso.dashboard'),
                default => route('dashboard'),
            };
        });

        View::composer(['auth.login', 'auth.register'], function ($view) {
            $view->with([
                'departments' => Department::orderBy('name')->get(['id', 'name']),
                'courses' => Course::orderBy('name')->get(['id', 'name', 'department_id'])->groupBy('department_id'),
            ]);
        });
    }
}
