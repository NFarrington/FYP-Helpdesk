<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
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
}
