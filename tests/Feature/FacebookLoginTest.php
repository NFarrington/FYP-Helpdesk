<?php

namespace Tests\Feature;

use App\Models\User;
use Exception;
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
     * Test when the user cancels the login.
     *
     * @return void
     */
    public function testCancelledByUser()
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
        $response->assertSessionHas('error', trans('auth.cancelled'));
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
            'error_reason' => str_random(),
        ]));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    /**
     * Test an exception is handled successfully.
     *
     * @return void
     */
    public function testOAuthExceptionHandled()
    {
        $this->mockProvider(['exception' => true]);

        $state = str_random(32);
        Session::put('login_oauth_state', $state);
        $response = $this->get(route('login.facebook.callback', [
            'code' => str_random(),
            'state' => $state,
        ]));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    /**
     * Test a missing email generates the expected error.
     *
     * @return void
     */
    public function testOAuthMissingEmail()
    {
        $this->mockProvider(['email' => false]);

        $state = str_random(32);
        Session::put('login_oauth_state', $state);
        $response = $this->get(route('login.facebook.callback', [
            'code' => str_random(),
            'state' => $state,
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('error', trans('auth.missing-email'));
    }

    /**
     * Mocks the OAuth provider for the duration of the test.
     *
     * @param array $options
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockProvider($options = [])
    {
        $options += ['email' => true, 'exception' => false];

        $mockProvider = $this->createMock(Facebook::class);

        if ($options['exception']) {
            $mockProvider->method('getAccessToken')->willThrowException(new Exception());
        } else {
            $mockProvider->method('getAccessToken')->willReturn(new AccessToken([
                'access_token' => str_random(100),
                'token_type' => 'bearer',
                'expires_in' => 5000000,
                'auth_type' => 'rerequest',
            ]));
        }

        $facebookUserStates = $options['email'] ? [] : ['no email'];
        $mockProvider->method('getResourceOwner')
            ->willReturn(factory(FacebookUser::class)->states($facebookUserStates)->make());

        $this->app->instance(Facebook::class, $mockProvider);

        return $mockProvider;
    }
}
