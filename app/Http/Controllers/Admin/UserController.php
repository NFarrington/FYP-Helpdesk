<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller as AdminController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users.index')->with('users', User::orderBy('id')->paginate(20));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.edit')->with([
            'user' => $user,
            'roles' => Role::orderBy('id')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User   $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required|string|max:250',
            'email' => 'required|email',
            'roles' => 'array',
        ]);

        $user->fill($request->only('name', 'email'));
        if ($user->isDirty('email')) {
            $user->email_verified = true;
        }
        if ($password = $request->input('password')) {
            $user->password = Hash::make($password);
        }
        $user->roles()->sync($request->input('roles'));
        $user->save();

        return redirect(route('admin.users.index'))->with('status', trans('user.updated'));
    }
}
