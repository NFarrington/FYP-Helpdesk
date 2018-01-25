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

    /**
     * Test a user can load the configuration page.
     *
     * @return void
     */
    public function testUserCanLoadConfigurationPage()
    {
        $response = $this->actingAs($this->user)->get(route('settings.2fa'));

        $response->assertStatus(200);
    }

    /**
     * Test a user can configure their 2FA and enable it.
     *
     * @return void
     */
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

    /**
     * Test 2FA configuration fails if user provided the wrong code.
     *
     * @return void
     */
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

    /**
     * Test user can load the verification page.
     *
     * @return void
     */
    public function testUserCanLoadVerificationPage()
    {
        $secret = str_random(16);
        $this->user->google2fa_secret = $secret;

        $this->actingAs($this->user);
        $response = $this->get(route('login.2fa'));

        $response->assertStatus(200);
    }

    /**
     * Test user is redirected to the verification page if unauthenticated.
     *
     * @return void
     */
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

    /**
     * Test user can verify their 2FA code.
     *
     * @return void
     */
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

    /**
     * Test verifying the wrong code fails.
     *
     * @return void
     */
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

    /**
     * Test user redirected if already authenticated.
     *
     * @return void
     */
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
