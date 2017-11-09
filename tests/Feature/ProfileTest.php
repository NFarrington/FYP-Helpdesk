<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
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
     * Test the profile page loads successfully.
     *
     * @return void
     */
    public function testProfilePageLoads()
    {
        $response = $this->actingAs($this->user)->get(route('users.show', $this->user));

        $response->assertStatus(200);
    }

    public function testPasswordChangeSucceeds()
    {
        $this->actingAs($this->user);

        $this->get(route('users.show', $this->user));
        $response = $this->put(route('users.update', $this->user), [
            'password' => 'secret',
            'new_password' => 'Password1234',
            'new_password_confirmation' => 'Password1234',
        ]);

        $response->assertRedirect(route('users.show', $this->user));
        $response->assertSessionHas('status', trans('passwords.updated'));
    }

    public function testPasswordChangeWrongPasswordFails()
    {
        $this->actingAs($this->user);

        $this->get(route('users.show', $this->user));
        $response = $this->put(route('users.update', $this->user), [
            'password' => 'wrong-password',
            'new_password' => 'Password1234',
            'new_password_confirmation' => 'Password1234',
        ]);

        //dd($response);

        $response->assertRedirect(route('users.show', $this->user));
        $response->assertSessionHasErrors('password', trans('auth.failed'));
    }
}
