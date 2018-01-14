<?php

namespace App\Listeners;

use App\Events\UserEmailChanged;
use App\Events\UserSaved;

class FireUserChangedEvents
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
     * @return void
     */
    public function handle(UserSaved $event)
    {
        $user = $event->user;

        if ($user->isDirty('email') && !$user->email_verified) {
            UserEmailChanged::dispatch($user);
        }
    }
}
