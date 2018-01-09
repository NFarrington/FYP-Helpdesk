<?php

namespace Tests\Feature;

use App\Events\UserEmailChanged;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
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
     * @throws \Exception
     */
    protected function setUp()
    {
        parent::setUp();

        $this->expectsEvents(UserEmailChanged::class);
        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);
    }

    /**
     * Test an email can be verified.
     *
     * @return void
     */
    public function testEmailCanBeVerified()
    {
        $token = str_random(40);
        factory(EmailVerification::class)->create([
            'token' => Hash::make($token),
            'user_id' => $this->user->id,
        ]);

        $response = $this->get(route('email.verify', $token));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('status', trans('user.email.verified'));

        $this->assertDatabaseHas($this->user->getTable(), ['email_verified' => 1]);
    }

    /**
     * Test an email fails verification if the token is not correct.
     *
     * @return void
     */
    public function testEmailVerificationFails()
    {
        $token = str_random(40);
        factory(EmailVerification::class)->create([
            'token' => Hash::make($token),
            'user_id' => $this->user->id,
        ]);

        $response = $this->get(route('email.verify', str_random(39)));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', trans('user.email.invalid_token'));

        $this->assertDatabaseHas($this->user->getTable(), ['email_verified' => 0]);
    }
}
