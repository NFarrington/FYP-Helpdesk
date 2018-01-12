<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\Facebook as FacebookProvider;
use League\OAuth2\Client\Provider\FacebookUser;

/**
 * @link https://developers.facebook.com/docs/facebook-login/manually-build-a-login-flow
 */
class FacebookController extends Controller
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
     * @var FacebookProvider
     */
    protected $provider;

    /**
     * Create a new controller instance.
     *
     * @param FacebookProvider $provider
     * @return void
     */
    public function __construct(FacebookProvider $provider)
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
        $authUrl = $this->provider->getAuthorizationUrl([
            'scope' => ['public_profile', 'email'],
            'auth_type' => 'rerequest',
        ]);
        Session::put('login_oauth_state', $this->provider->getState());

        // redirect to auth url
        return redirect()->to($authUrl);
    }

    /**
     * Handle the user redirected from the OAuth provider.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \League\OAuth2\Client\Provider\Exception\FacebookProviderException
     * @throws AuthenticationException
     */
    public function callback(Request $request)
    {
        $oauthState = Session::pull('login_oauth_state');
        if ($request->input('state') !== $oauthState) {
            abort(401, 'Unauthorized', ['WWW-Authenticate' => 'OAuth realm='.config('app.url')]);
        }

        if ($request->has('error')) {
            if ($request->input('error_reason') === 'user_denied') {
                return redirect()->route('login')->with('error', 'Facebook login cancelled.');
            }

            return redirect()->route('login')
                ->with('error', "{$request->input('error_code')} {$request->input('error_description')}");
        }

        try {
            $token = $this->provider->getAccessToken('authorization_code', [
                'code' => $request->input('code'),
            ]);
        } catch (IdentityProviderException $e) { // @codeCoverageIgnore
            abort(400, 'Bad Request'); // @codeCoverageIgnore
        }

        /** @var FacebookUser $user */
        $user = $this->provider->getResourceOwner($token);

        return $this->processFacebookUserLogin($request, $user);
    }

    /**
     * Processes the login request.
     *
     * @param Request $request
     * @param FacebookUser $facebookUser
     * @return \Illuminate\Http\Response
     * @throws AuthenticationException
     */
    protected function processFacebookUserLogin(Request $request, FacebookUser $facebookUser)
    {
        $this->validateFacebookUser($facebookUser);

        /** @var User $user */
        $user = User::where('facebook_id', $facebookUser->getId())
            ->orWhere('email', $facebookUser->getEmail())
            ->orderByDesc('facebook_id') // prioritise result with a matching ID
            ->first()
            ?: User::make([
            'name' => $facebookUser->getName(),
            'email' => $facebookUser->getEmail(),
        ]);

        $user->facebook_id = $facebookUser->getId();
        $user->facebook_data = $facebookUser->toArray();
        $user->save();

        Auth::login($user, true);

        return $this->sendLoginResponse($request);
    }

    /**
     * Validates the integrity of the FacebookUser object.
     *
     * @param FacebookUser $facebookUser
     * @throws AuthenticationException
     */
    protected function validateFacebookUser(FacebookUser $facebookUser)
    {
        if ($facebookUser->getEmail() === null) {
            Session::flash('error', 'You must allow the email permission to enable login via Facebook.');

            throw new AuthenticationException();
        }
    }
}
