<?php

namespace App\Listeners\Auth;

use App\Models\User;
use App\Notifications\LoginSuccessful;
use Illuminate\Auth\Events\Login;

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

        $user->notify(new LoginSuccessful());
    }
}
