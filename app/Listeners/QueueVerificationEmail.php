<?php

namespace App\Listeners;

use App\Events\UserSaved;
use App\Models\EmailVerification;
use App\Notifications\VerifyEmail as EmailNotification;
use Illuminate\Support\Facades\Hash;

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
     * @param \App\Events\UserSaved $event
     * @return void
     */
    public function handle(UserSaved $event)
    {
        $user = $event->user;

        if ($user->isDirty('email') && !$user->email_verified) {
            $key = app_key();

            $user->email_verified = false;

            $token = hash_hmac('sha256', str_random(40), $key);
            $verification = $user->emailVerification ?: new EmailVerification();
            $verification->token = Hash::make($token);
            $verification->user()->associate($user);
            $verification->save();

            $user->notify(new EmailNotification($token));
        }
    }
}
