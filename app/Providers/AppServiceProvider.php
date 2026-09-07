<?php

namespace App\Providers;

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
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\View::composer('template', function ($view) {
            $currentHour = (int) now()->format('H');
            if ($currentHour >= 4 && $currentHour < 11) {
                $serverGreeting = 'Selamat pagi';
            } elseif ($currentHour >= 11 && $currentHour < 15) {
                $serverGreeting = 'Selamat siang';
            } elseif ($currentHour >= 15 && $currentHour < 18) {
                $serverGreeting = 'Selamat sore';
            } else {
                $serverGreeting = 'Selamat malam';
            }

            \Carbon\Carbon::setLocale('id');
            $serverDate = now()->translatedFormat('l, d F Y');
            $serverClock = now()->format('H:i:s') . ' WIB';

            $view->with(compact('serverGreeting', 'serverDate', 'serverClock'));
        });
    }
}
