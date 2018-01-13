<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use League\OAuth2\Client\Provider\Google as GoogleProvider;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

/**
 * @link https://developers.google.com/identity/protocols/OAuth2WebServer
 */
class GoogleController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * The OAuth provider instance.
     *
     * @var GoogleProvider
     */
    protected $provider;

    /**
     * Create a new controller instance.
     *
     * @param GoogleProvider $provider
     * @return void
     */
    public function __construct(GoogleProvider $provider)
    {
        $this->provider = $provider;
        $this->middleware('guest');
    }

    /**
     * Handle the login request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login()
    {
        $authUrl = $this->provider->getAuthorizationUrl();
        Session::put('login_oauth_state', $this->provider->getState());

        // redirect to auth url
        return redirect()->to($authUrl);
    }

    /**
     * Handle the user redirected from the OAuth provider.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws AuthenticationException
     */
    public function callback(Request $request)
    {
        $oauthState = Session::pull('login_oauth_state');
        if ($request->input('state') !== $oauthState) {
            abort(401, 'Unauthorized', ['WWW-Authenticate' => 'OAuth realm='.config('app.url')]);
        }

        if ($request->has('error')) {
            $this->handleError();
        }

        try {
            $user = $this->getUser($request->input('code'));
        } catch (Exception $e) {
            $this->handleError($e);
        }

        return $this->processLogin($request, $user);
    }

    /**
     * Retrieve the User instance with a given OAuth code.
     *
     * @param  string $code
     * @return GoogleUser|ResourceOwnerInterface
     */
    protected function getUser($code)
    {
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code,
        ]);

        return $this->provider->getResourceOwner($token);
    }

    /**
     * Processes the login request.
     *
     * @param Request $request
     * @param GoogleUser $googleUser
     * @return \Illuminate\Http\Response
     */
    protected function processLogin(Request $request, GoogleUser $googleUser)
    {
        /** @var User $user */
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->orderByDesc('google_id') // prioritise result with a matching ID
            ->first()
            ?: User::make([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
        ]);

        $user->google_id = $googleUser->getId();
        $user->google_data = $googleUser->toArray();
        $user->save();

        Auth::login($user, true);

        return $this->sendLoginResponse($request);
    }

    /**
     * Handle a given exception.
     *
     * @param $e
     * @throws AuthenticationException
     */
    protected function handleError($e = null)
    {
        if ($e) {
            report($e);
        }

        Session::flash('error', 'Failed to log in - please try again later.');

        throw new AuthenticationException();
    }
}
