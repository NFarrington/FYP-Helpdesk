<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Token\AccessToken;
use Tests\TestCase;

class FacebookLoginTest extends TestCase
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
    public function testInitialLoginRedirects()
    {
        $response = $this->post(route('login.facebook'));

        $response->assertRedirect();
        $this->assertContains('https://www.facebook.com/v2.11/dialog/oauth', $response->headers->get('Location'));
    }

    /**
     * Test the login page redirects authenticated users.
     *
     * @return void
     */
    public function testOAuthCallbackSucceeds()
    {
        $this->mockProvider();

        $state = str_random(32);
        Session::put('login_oauth_state', $state);
        $response = $this->get(route('login.facebook.callback', [
            'code' => str_random(),
            'state' => $state,
        ]));

        $response->assertRedirect(route('home'));
    }

    /**
     * Test the state must remain identical.
     *
     * @return void
     */
    public function testOAuthCallbackStateMismatchFails()
    {
        $this->mockProvider();

        $state = str_random(32);
        Session::put('login_oauth_state', $state);
        $response = $this->get(route('login.facebook.callback', [
            'code' => str_random(),
            'state' => str_random(31),
        ]));

        $response->assertStatus(401);
    }

    /**
     * Test the login page redirects authenticated users.
     *
     * @return void
     */
    public function testOAuthErrorHandledSuccessfully()
    {
        $this->mockProvider();

        $state = str_random(32);
        Session::put('login_oauth_state', $state);
        $response = $this->get(route('login.facebook.callback', [
            'state' => $state,
            'error' => str_random(),
            'error_reason' => 'user_denied',
        ]));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Facebook login cancelled.');

        Session::put('login_oauth_state', $state);
        $response = $this->get(route('login.facebook.callback', [
            'state' => $state,
            'error' => str_random(),
            'error_reason' => str_random(),
        ]));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function testOAuthMissingEmail()
    {

        $mockProvider = $this->createMock(Facebook::class);
        $mockProvider->method('getAccessToken')->willReturn(new AccessToken([
            'access_token' => str_random(100),
            'token_type' => 'bearer',
            'expires_in' => 5000000,
            'auth_type' => 'rerequest',
        ]));
        $mockProvider->method('getResourceOwner')->willReturn(new FacebookUser([
            'id' => mt_rand(),
            'name' => 'Neil Farrington',
            'first_name' => 'First',
            'last_name' => 'Last',
            'picture' => [
                'data' => [
                    'url' => 'url_to_jpg',
                    'is_silhouette' => false,
                ],
            ],
            'cover' => [
                'source' => 'url_to_jpg',
                'id' => mt_rand(),
            ],
            'gender' => 'male',
            'locale' => 'en_GB',
            'link' => 'https://www.facebook.com/app_scoped_user_id/'.mt_rand().'/',
            'timezone' => 0,
            'age_range' => [
                'min' => 21,
            ],
        ]));
        $this->app->instance(Facebook::class, $mockProvider);

        $state = str_random(32);
        Session::put('login_oauth_state', $state);
        $response = $this->get(route('login.facebook.callback', [
            'code' => str_random(),
            'state' => $state,
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You must allow the email permission to enable login via Facebook.');
    }

    protected function mockProvider()
    {
        $mockProvider = $this->createMock(Facebook::class);
        $mockProvider->method('getAccessToken')->willReturn(new AccessToken([
            'access_token' => str_random(100),
            'token_type' => 'bearer',
            'expires_in' => 5000000,
            'auth_type' => 'rerequest',
        ]));
        $mockProvider->method('getResourceOwner')->willReturn(new FacebookUser([
            'id' => mt_rand(),
            'name' => 'Neil Farrington',
            'first_name' => 'First',
            'last_name' => 'Last',
            'email' => 'email@example.com',
            'picture' => [
                'data' => [
                    'url' => 'url_to_jpg',
                    'is_silhouette' => false,
                ],
            ],
            'cover' => [
                'source' => 'url_to_jpg',
                'id' => mt_rand(),
            ],
            'gender' => 'male',
            'locale' => 'en_GB',
            'link' => 'https://www.facebook.com/app_scoped_user_id/'.mt_rand().'/',
            'timezone' => 0,
            'age_range' => [
                'min' => 21,
            ],
        ]));

        $this->app->instance(Facebook::class, $mockProvider);

        return $mockProvider;
    }
}
