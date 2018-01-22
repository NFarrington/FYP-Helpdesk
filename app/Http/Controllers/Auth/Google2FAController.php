<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class Google2FAController extends Controller
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

    public function showForm(Request $request)
    {
        $authenticator = app(Authenticator::class)->boot($request);

        if ($authenticator->isAuthenticated()) {
            return redirect()->route('home');
        }

        return view('auth.2fa.index');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|numeric',
        ]);

        $authenticator = app(Authenticator::class)->boot($request);

        $valid = $authenticator->isAuthenticated();

        if ($valid) {
            return redirect()->intended();
        }

        return redirect()->back()->with('error', trans('auth.2fa.failed'));
    }
}
