<?php

namespace App\Http\Controllers;

use App\Models\User;
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
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
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $this->validate($request, [
            'password' => 'required|string',
            'new_password' => 'required|string|confirmed|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).*$/',
        ], ['regex' => trans('passwords.requirements')]);

        if (!Auth::validate($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'password' => [trans('auth.failed')],
            ]);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return redirect(route('users.show', $user))
            ->with('status', trans('passwords.updated'));
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
}
