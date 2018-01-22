<?php

namespace App\Http\Controllers;

use App\Models\User;
use Google2FA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Client\Provider\Google;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @codeCoverageIgnore
     * @todo    implement
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @codeCoverageIgnore
     * @todo    implement
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @codeCoverageIgnore
     * @todo    implement
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return view('users.view')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @codeCoverageIgnore
     * @todo    implement
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
            'new_password' => 'nullable|string|confirmed|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).*$/',
        ], ['regex' => trans('passwords.requirements')]);

        if (!Auth::validate(['email' => $user->email, 'password' => $request->input('password')])) {
            throw ValidationException::withMessages([
                'password' => [trans('auth.failed')],
            ]);
        }

        $user->email = $request->input('email');
        if ($newPassword = $request->input('new_password')) {
            $user->password = Hash::make($newPassword);
        }

        $user->save();

        return redirect(route('users.show', $user))
            ->with('status', trans('user.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @codeCoverageIgnore
     * @todo    implement
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function show2FAForm(Request $request)
    {
        $secret = $request->user()->google2fa_secret;

        if (!$secret) {
            $secret = $request->session()->get('google2fa-secret');
        }

        if (!$secret) {
            $secret = Google2FA::generateSecretKey();
        }

        $request->session()->put('google2fa-secret', $secret);

        $qrCode = Google2FA::getQRCodeInline(
            config('app.name'),
            $request->user()->email,
            $secret,
            250
        );

        return view('settings.2fa')->with([
            'qrCode' => $qrCode,
            'secret' => $secret,
        ]);
    }

    public function register2FA(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|numeric',
        ]);

        $code = $request->input('code');

        $valid = Google2FA::verifyKey($request->session()->get('google2fa-secret'), $code);

        if ($valid) {
            $secret = $request->session()->pull('google2fa-secret');
            $user = $request->user();

            $user->google2fa_secret = $secret;
            $user->save();

            return redirect()->route('users.show', $user)->with('status', trans('auth.2fa.configured'));
        }

        return redirect()->back()->with('error', trans('auth.2fa.failed'));
    }
}
