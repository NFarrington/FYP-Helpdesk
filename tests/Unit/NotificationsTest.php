<?php

namespace Tests\Unit;

use App\Models\User;
use App\Notifications\EmailVerification;
use App\Notifications\LoginFailed;
use App\Notifications\LoginSuccessful;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test user.
     *
     * @var User
     */
    protected $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * Test the email verification notification.
     *
     * @return void
     */
    public function testEmailVerificationNotification()
    {
        $token = str_random(40);
        $notification = new EmailVerification($token);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);

        $this->assertContains('Verify Email Address', $mail->subject);
        $this->assertArraySubset(['oldEmail', 'newEmail'], array_keys($db));
    }

    /**
     * Test the successful login notification.
     *
     * @return void
     */
    public function testLoginSuccessfulNotification()
    {
        $notification = new LoginSuccessful();
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);

        $this->assertContains('Successful Login Attempt', $mail->subject);
        $this->assertEmpty($db);
    }

    /**
     * Test the failed login notification.
     *
     * @return void
     */
    public function testLoginFailedNotification()
    {
        $notification = new LoginFailed();
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);

        $this->assertContains('Failed Login Attempt', $mail->subject);
        $this->assertEmpty($db);
    }
}
