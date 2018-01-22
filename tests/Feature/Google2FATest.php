<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use Tests\TestCase;

class Google2FATest extends TestCase
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

    public function testUserCanLoadConfigurationPage()
    {
        $response = $this->actingAs($this->user)->get(route('settings.2fa'));

        $response->assertStatus(200);
    }

    public function testUserCanConfigure2FA()
    {
        $mock = $this->createMock(Google2FA::class);
        $mock->method('verifyKey')->willReturn(true);
        $this->app->instance('pragmarx.google2fa', $mock);

        $this->actingAs($this->user);
        $this->get(route('settings.2fa'));
        $response = $this->post(route('settings.2fa'), ['code' => mt_rand(0, 999999)]);

        $response->assertRedirect(route('users.show', $this->user));
    }

    public function testUserConfigurationFails()
    {
        $mock = $this->createMock(Google2FA::class);
        $mock->method('verifyKey')->willReturn(false);
        $this->app->instance('pragmarx.google2fa', $mock);

        $this->actingAs($this->user);
        $this->get(route('settings.2fa'));
        $response = $this->post(route('settings.2fa'), ['code' => mt_rand(0, 999999)]);

        $response->assertRedirect(route('settings.2fa'));
    }

    public function testUserCanLoadVerificationPage()
    {
        $secret = str_random(16);
        $this->user->google2fa_secret = $secret;

        $this->actingAs($this->user);
        $response = $this->get(route('login.2fa'));

        $response->assertStatus(200);
    }

    public function testUserRedirectedToVerificationPage()
    {
        $mock = $this->createMock(Google2FA::class);
        $mock->method('verifyKey')->willReturn(false);
        $this->app->instance('pragmarx.google2fa', $mock);

        $secret = str_random(16);
        $this->user->google2fa_secret = $secret;

        $this->actingAs($this->user);
        $response = $this->get(route('home'));

        $response->assertRedirect(route('login.2fa'));
    }

    public function testUserCanSubmitVerificationPage()
    {
        $mock = $this->createMock(Google2FA::class);
        $mock->method('verifyKey')->willReturn(true);
        $this->app->instance('pragmarx.google2fa', $mock);

        $secret = str_random(16);
        $this->user->google2fa_secret = $secret;

        $this->actingAs($this->user);
        $this->get(route('login.2fa'));
        $response = $this->post(route('login.2fa'), ['code' => mt_rand(0, 999999)]);

        $response->assertRedirect(route('home'));
    }

    public function testVerificationFails()
    {
        $mock = $this->createMock(Google2FA::class);
        $mock->method('verifyKey')->willReturn(false);
        $this->app->instance('pragmarx.google2fa', $mock);

        $secret = str_random(16);
        $this->user->google2fa_secret = $secret;

        $this->actingAs($this->user);
        $this->get(route('login.2fa'));
        $response = $this->post(route('login.2fa'), ['code' => mt_rand(0, 999999)]);

        $response->assertRedirect(route('login.2fa'));
    }

    public function testUserRedirectedFromVerificationPage()
    {
        $mock = $this->createMock(Authenticator::class);
        $mock->method('boot')->willReturnSelf();
        $mock->method('isAuthenticated')->willReturn(true);
        $this->app->instance(Authenticator::class, $mock);

        $secret = str_random(16);
        $this->user->google2fa_secret = $secret;

        $this->actingAs($this->user);
        $response = $this->get(route('login.2fa'));

        $response->assertRedirect(route('home'));
    }
}
