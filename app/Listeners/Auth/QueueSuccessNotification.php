<?php

namespace App\Listeners\Auth;

use App\Models\User;
use App\Notifications\LoginSuccessful;
use Illuminate\Auth\Events\Login;
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        /** @var User $user */
        $user = $event->user;

        if (!Session::exists('login_notification_sent') && !$user->wasRecentlyCreated) {
            $user->notify(new LoginSuccessful());
            Session::put('login_notification_sent', true);
        }
    }
}
