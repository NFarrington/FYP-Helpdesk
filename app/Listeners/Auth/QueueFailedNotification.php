<?php

namespace App\Listeners\Auth;

use App\Models\User;
use App\Notifications\LoginFailed;
use Illuminate\Auth\Events\Failed;

class QueueFailedNotification
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
     * @param  Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
        /** @var User $user */
        $user = $event->user;

        if ($user !== null) {
            $user->notify(new LoginFailed());
        }
    }
}
