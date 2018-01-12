<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\OAuth2\Client\Provider\Facebook;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
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
    }
}
