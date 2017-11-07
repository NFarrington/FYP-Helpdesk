<?php

namespace Tests\Unit;

use App\Models\User;
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
     * [Summary]
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
     * [Summary]
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
