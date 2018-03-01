<?php

namespace App\Http\Controllers\Api;

use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;

class PermissionController extends Controller
{
    /**
     * The service.
     *
     * @var PermissionService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param PermissionService $service
     */
    public function __construct(PermissionService $service)
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
        $this->authorize('view', Permission::class);

        $permissions = $this->service->getViewableBy($request->user());

        return Resource::collection($permissions);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Permission $permission)
    {
        $this->authorize('view', $permission);

        return response()->json($permission->attributesToArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Permission $permission)
    {
        $this->authorize('update', $permission);

        $attributes = $this->validate($request, [
            'name' => 'required|string|max:250',
            'description' => 'required|string|max:250',
            'roles' => 'array',
        ]);

        $this->service->update($permission, $attributes);

        return response()->json($permission);
    }
}
