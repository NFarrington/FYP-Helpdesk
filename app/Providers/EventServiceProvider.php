<?php

namespace App\Providers;

use App\Events;
use App\Listeners;
use Illuminate\Auth\Events as AuthEvents;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AuthEvents\Login::class => [
            Listeners\Auth\QueueSuccessNotification::class,
        ],

        AuthEvents\Failed::class => [
            Listeners\Auth\QueueFailedNotification::class,
        ],

        Events\UserSaved::class => [
            Listeners\FireUserChangedEvents::class,
        ],

        Events\UserEmailChanged::class => [
            Listeners\QueueVerificationEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
