<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
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

        return response()->json($users);
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

        return response()->json($user->attributesToArray());
    }
}
