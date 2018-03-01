<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class RoleController extends Controller
{
    /**
     * The service.
     *
     * @var RoleService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param RoleService $service
     */
    public function __construct(RoleService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('view', Role::class);

        $roles = $this->service->getViewableBy($request->user());

        return Resource::collection($roles);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Role $role)
    {
        $this->authorize('view', $role);

        return response()->json($role->attributesToArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        $attributes = $this->validate($request, [
            'name' => 'required|string|max:250',
            'description' => 'required|string|max:250',
            'permissions' => 'array',
        ]);

        $this->service->update($role, $attributes);

        return response()->json($role->attributesToArray());
    }
}
