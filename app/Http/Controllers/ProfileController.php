<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
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
        $this->middleware('auth');

        $this->service = $service;
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
        $attributes = $this->validate($request, $this->rules(), $this->messages());

        $this->service->update($request->user(), $attributes);

        return redirect()->route('profile.show')
            ->with('status', trans('user.updated'));
    }

    /**
     * Get the validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
            'new_password' => 'nullable|string|confirmed|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).*$/',
        ];
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    protected function messages()
    {
        return ['regex' => trans('passwords.requirements')];
    }
}
