<?php

namespace App\Http\Controllers;

use App\Models\User;
use Google2FA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return view('users.view')->with('user', $request->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
            'new_password' => 'nullable|string|confirmed|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).*$/',
        ], ['regex' => trans('passwords.requirements')]);

        $user = $request->user();
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

        return redirect()->route('profile.show')
            ->with('status', trans('user.updated'));
    }

    /**
     * Show the form for configuring 2FA.
     *
     * @param Request $request
     * @return $this
     */
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

    /**
     * Register the user's 2FA configuration using the code provided.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

            return redirect()->route('profile.show')->with('status', trans('auth.2fa.configured'));
        }

        return redirect()->back()->with('error', trans('auth.2fa.failed'));
    }
}
