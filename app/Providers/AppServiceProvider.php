<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\Google;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapThree();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Facebook::class, function ($app) {
            return new Facebook([
                'clientId'          => config('services.facebook.id'),
                'clientSecret'      => config('services.facebook.secret'),
                'redirectUri'       => route('login.facebook.callback'),
                'graphApiVersion'   => config('services.facebook.version'),
            ]);
        });

        $this->app->singleton(Google::class, function ($app) {
            return new Google([
                'clientId'      => config('services.google.id'),
                'clientSecret'  => config('services.google.secret'),
                'redirectUri'   => config('services.google.redirect_uri') ?? route('login.google.callback'),
            ]);
        });
    }
}
