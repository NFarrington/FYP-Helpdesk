<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller as AdminController;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends AdminController
{
    /**
     * The service.
     *
     * @var UserService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = $this->service->getViewableBy($request->user());

        return view('admin.users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create')->with([
            'user' => new User(),
            'roles' => Role::query()->orderBy('id')->get(),
            'departments' => Department::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $this->validate($request, [
            'name' => 'required|string|max:250',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).*$/',
            'roles' => 'array',
            'departments' => 'array',
        ], [
            'regex' => trans('passwords.requirements'),
        ]);

        $this->service->create($attributes);

        return redirect()->route('admin.users.index')
            ->with('status', trans('user.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        Session::reflash();

        return redirect()->route('admin.users.edit', $user);
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
            'roles' => Role::query()->orderBy('id')->get(),
            'departments' => Department::query()->orderBy('name')->get(),
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
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).*$/',
            'roles' => 'array',
            'departments' => 'array',
        ], [
            'regex' => trans('passwords.requirements'),
        ]);

        $user->fill($request->only('name', 'email'));
        if ($user->isDirty('email')) {
            $user->email_verified = true;
        }
        if ($password = $request->input('password')) {
            $user->password = Hash::make($password);
        }
        $user->roles()->sync($request->input('roles'));
        $user->departments()->sync($request->input('departments'));
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('status', trans('user.updated'));
    }
}
