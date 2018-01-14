<?php

namespace Tests\Feature;

use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessToken;
use Tests\TestCase;

class GoogleLoginTest extends TestCase
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
        $response = $this->post(route('login.google'));

        $response->assertRedirect();
        $this->assertContains('https://accounts.google.com/o/oauth2/auth', $response->headers->get('Location'));
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
        $response = $this->get(route('login.google.callback', [
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
        $response = $this->get(route('login.google.callback', [
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
        $response = $this->get(route('login.google.callback', [
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
        $response = $this->get(route('login.google.callback', [
            'code' => str_random(),
            'state' => $state,
        ]));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
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

        $mockProvider = $this->createMock(Google::class);

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

        $userStates = $options['email'] ? [] : ['no email'];
        $mockProvider->method('getResourceOwner')
            ->willReturn(factory(GoogleUser::class)->states($userStates)->make());

        $this->app->instance(Google::class, $mockProvider);

        return $mockProvider;
    }
}
