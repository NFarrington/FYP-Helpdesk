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
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

abstract class OAuthController extends Controller
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
     * @var AbstractProvider
     */
    protected $provider;

    /**
     * The authorization URL options.
     *
     * @var array
     */
    protected $authUrlOptions = [];

    /**
     * The model fields to store the data in.
     *
     * @var array
     */
    protected $dataFields = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setUpProvider();

        $this->middleware('guest');
    }

    /**
     * Resolves the OAuth provider.
     *
     * @return void
     */
    abstract protected function setUpProvider();

    /**
     * Handle the login request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login()
    {
        $authUrl = $this->provider->getAuthorizationUrl($this->authUrlOptions);

        Session::put('login_oauth_state', $this->provider->getState());

        return redirect()->to($authUrl);
    }

    /**
     * Handle the user redirected from the OAuth provider.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
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
                return redirect()->route('login')->with('error', trans('auth.cancelled'));
            }

            $this->reportError();
        }

        try {
            $user = $this->getUser($request->input('code'));
        } catch (Exception $e) {
            $this->reportError($e);
        }

        return $this->processLogin($request, $user);
    }

    /**
     * Retrieve the FacebookUser instance with a given OAuth code.
     *
     * @param  string $code
     * @return ResourceOwnerInterface
     */
    protected function getUser($code)
    {
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code,
        ]);

        return $this->provider->getResourceOwner($token);
    }

    /**
     * Handle a given exception.
     *
     * @param $e
     * @throws AuthenticationException
     */
    protected function reportError($e = null)
    {
        if ($e) {
            report($e);
        }

        Session::flash('error', trans('auth.system-failed'));

        throw new AuthenticationException();
    }

    /**
     * Processes the login request.
     *
     * @param Request $request
     * @param FacebookUser|GoogleUser $oAuthUser
     * @return \Illuminate\Http\Response
     * @throws AuthenticationException
     */
    protected function processLogin(Request $request, $oAuthUser)
    {
        if ($oAuthUser->getEmail() === null) {
            Session::flash('error', trans('auth.missing-email'));

            throw new AuthenticationException();
        }

        $idField = $this->dataFields['id'];
        $dataField = $this->dataFields['data'];

        /** @var User $user */
        $user = User::where($idField, $oAuthUser->getId())
            ->orWhere('email', $oAuthUser->getEmail())
            ->orderByDesc($idField) // prioritise result with a matching ID
            ->first()
            ?: User::make([
                'name' => $oAuthUser->getName(),
                'email' => $oAuthUser->getEmail(),
            ]);

        $user->$idField = $oAuthUser->getId();
        $user->$dataField = $oAuthUser->toArray();
        $user->save();

        Auth::login($user, true);

        return $this->sendLoginResponse($request);
    }
}
