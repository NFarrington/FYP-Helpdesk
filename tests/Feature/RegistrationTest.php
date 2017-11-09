<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\LoginSuccessful;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationTest extends TestCase
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
     * Test the registration page loads successfully.
     *
     * @return void
     */
    public function testRegistrationPageLoads()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    /**
     * Test the registration page redirects authenticated users.
     *
     * @return void
     */
    public function testRegistrationPageRedirectsAuthenticatedUsers()
    {
        $response = $this->actingAs($this->user)->get(route('register'));

        $response->assertRedirect(route('home'));
    }

    /**
     * Test registration succeeds.
     *
     * @return void
     */
    public function testRegistrationSucceeds()
    {
        Notification::fake();

        $user = factory(User::class)->make();

        $this->get(route('register'));
        $response = $this->post(route('register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'Password1234',
            'password_confirmation' => 'Password1234',
        ]);

        $response->assertRedirect(route('home'));

        Notification::assertNotSentTo($user, LoginSuccessful::class, 1);
    }
}
