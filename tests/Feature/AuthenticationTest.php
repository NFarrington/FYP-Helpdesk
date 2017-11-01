<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
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
     * Test authentication succeeds with the correct credentials.
     *
     * @return void
     */
    public function testAuthenticationSucceeds()
    {
        $this->get(route('login'));
        $response = $this->post(route('login'), ['email' => $this->user->email, 'password' => 'secret']);

        $response->assertRedirect(route('home'));
    }

    /**
     * Test authentication fails with incorrect credentials.
     *
     * @return void
     */
    public function testAuthenticationFails()
    {
        $this->get(route('login'));
        $response = $this->post(route('login'), ['email' => $this->user->email, 'password' => 'wrong-password']);

        $response->assertRedirect(route('login'));
    }
}
