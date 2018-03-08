<?php

namespace App\Listeners\Auth;

use App\Models\User;
use App\Notifications\LoginSuccessful;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class QueueSuccessNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        /** @var User $user */
        $user = $event->user;

        if (!Auth::viaRemember() && !$user->wasRecentlyCreated) {
            $user->notify(new LoginSuccessful());
        }
    }
}
