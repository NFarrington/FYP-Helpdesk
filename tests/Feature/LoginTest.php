<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\LoginFailed;
use App\Notifications\LoginSuccessful;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LoginTest extends TestCase
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
     * Test the login page loads successfully.
     *
     * @return void
     */
    public function testLoginPageLoads()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    /**
     * Test the login page redirects authenticated users.
     *
     * @return void
     */
    public function testLoginPageRedirectsAuthenticatedUsers()
    {
        $response = $this->actingAs($this->user)->get(route('login'));

        $response->assertRedirect(route('home'));
    }

    /**
     * Test authentication succeeds with the correct credentials.
     *
     * @return void
     */
    public function testAuthenticationSucceeds()
    {
        Notification::fake();

        $this->get(route('login'));
        $response = $this->post(route('login'), ['email' => $this->user->email, 'password' => 'secret']);

        $response->assertRedirect(route('home'));

        Notification::assertSentTo($this->user, LoginSuccessful::class, 1);
    }

    /**
     * Test authentication fails with incorrect credentials.
     *
     * @return void
     */
    public function testAuthenticationFails()
    {
        Notification::fake();

        $this->get(route('login'));
        $response = $this->post(route('login'), ['email' => $this->user->email, 'password' => 'wrong-password']);

        $response->assertRedirect(route('login'));

        Notification::assertSentTo($this->user, LoginFailed::class, 1);
    }
}
