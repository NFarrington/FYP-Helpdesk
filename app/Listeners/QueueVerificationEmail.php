<?php

namespace App\Listeners;

use App\Events\UserSaved;
use App\Models\EmailVerification;
use App\Notifications\EmailVerification as EmailNotification;

class QueueVerificationEmail
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(\Illuminate\Foundation\Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle the event.
     *
     * @return void
     * @throws \Exception
     */
    public function handle(UserSaved $event)
    {
        $user = $event->user;

        $user->email_confirmed = false;
        if ($verification = $user->emailVerification) {
            $verification->delete();
        }

        $token = hash_hmac('sha256', str_random(40), $this->app['config']['app.key']);
        $verification = new EmailVerification(['token' => $token]);
        $verification->user()->associate($user);
        $verification->save();

        $user->notify(new EmailNotification($token));
    }
}
