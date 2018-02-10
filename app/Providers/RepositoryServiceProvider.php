<?php

namespace App\Providers;

use App\Repositories\AnnouncementRepository;
use App\Repositories\EloquentAnnouncementRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AnnouncementRepository::class, EloquentAnnouncementRepository::class);
    }
}
