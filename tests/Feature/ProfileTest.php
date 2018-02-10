<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\EmailVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
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
        $response = $this->actingAs($this->user)->get(route('profile.show'));

        $response->assertStatus(200);
    }

    public function testEmailChangeSucceeds()
    {
        Notification::fake();

        $this->actingAs($this->user);

        $this->get(route('profile.show'));
        $response = $this->put(route('profile.update'), [
            'email' => factory(User::class)->make()->email,
            'password' => 'secret',
            'new_password' => '',
            'new_password_confirmation' => '',
        ]);

        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('status', trans('user.updated'));

        Notification::assertSentTo($this->user, EmailVerification::class, 1);
    }

    public function testPasswordChangeSucceeds()
    {
        $this->actingAs($this->user);

        $this->get(route('profile.show'));
        $response = $this->put(route('profile.update'), [
            'email' => $this->user->email,
            'password' => 'secret',
            'new_password' => 'Password1234',
            'new_password_confirmation' => 'Password1234',
        ]);

        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('status', trans('user.updated'));
    }

    public function testPasswordChangeWrongPasswordFails()
    {
        $this->actingAs($this->user);

        $this->get(route('profile.show'));
        $response = $this->put(route('profile.update'), [
            'email' => $this->user->email,
            'password' => 'wrong-password',
            'new_password' => 'Password1234',
            'new_password_confirmation' => 'Password1234',
        ]);

        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHasErrors('password', trans('auth.failed'));
    }
}
