<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VerifyEmailController extends Controller
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
     * Verify the email address using the token provided.
     *
     * @param Request $request
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function verifyEmail(Request $request, $token)
    {
        /** @var User $user */
        $user = $request->user();

        $verification = $user->emailVerification;
        if (!$verification || !Hash::check($token, $verification->token)) {
            return redirect(route('home'))->with('error', trans('user.email.invalid_token'));
        }

        $user->email_confirmed = true;
        $user->save();

        $verification->delete();

        return redirect(route('home'))->with('status', trans('user.email.verified'));
    }
}
