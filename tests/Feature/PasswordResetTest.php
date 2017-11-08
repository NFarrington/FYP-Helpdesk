<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
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
     * Test the reset page loads successfully.
     *
     * @return void
     */
    public function testResetRequestPageLoads()
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
    }

    /**
     * Test the reset page can be submitted successfully.
     *
     * @return void
     */
    public function testResetRequestSucceeds()
    {
        Notification::fake();

        $this->get(route('password.request'));
        $response = $this->post(route('password.email'), ['email' => $this->user->email]);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHas('status', 'We have e-mailed your password reset link!');

        Notification::assertSentTo($this->user, ResetPassword::class, 1);
    }

    /**
     * Test the reset page can be submitted successfully.
     *
     * @return void
     */
    public function testResetPageLoads()
    {
        $token = Password::broker()->createToken($this->user);
        $response = $this->get(route('password.reset', $token));

        $response->assertStatus(200);
    }

    /**
     * Test the reset page can be submitted successfully.
     *
     * @return void
     */
    public function testResetSucceeds()
    {
        Notification::fake();

        $token = Password::broker()->createToken($this->user);
        $this->get(route('password.reset', $token));
        $response = $this->post(route('password.request'), [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'Test1234',
            'password_confirmation' => 'Test1234',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('status', 'Your password has been reset!');
    }
}
