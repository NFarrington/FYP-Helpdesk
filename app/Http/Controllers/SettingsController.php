<?php

namespace App\Http\Controllers;

use Google2FA;
use Illuminate\Http\Request;

class SettingsController extends Controller
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
