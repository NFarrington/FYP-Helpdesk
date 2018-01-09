<?php

namespace App\Listeners;

use App\Events\UserEmailChanged;
use App\Models\EmailVerification;
use App\Notifications\EmailVerification as EmailNotification;
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
     * @return void
     * @throws \Exception
     */
    public function handle(UserEmailChanged $event)
    {
        $key = $this->app['config']['app.key'];
        if (starts_with($key, 'base64:')) {
            $key = base64_decode(mb_substr($key, 7));
        }

        $user = $event->user;

        $user->email_verified = false;
        if ($verification = $user->emailVerification) {
            $verification->delete();
        }

        $token = hash_hmac('sha256', str_random(40), $key);
        $verification = new EmailVerification();
        $verification->token = Hash::make($token);
        $verification->user()->associate($user);
        $verification->save();

        $user->notify(new EmailNotification($token));
    }
}
