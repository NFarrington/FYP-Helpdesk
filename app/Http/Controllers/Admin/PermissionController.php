<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use App\Services\PermissionService;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = $this->service->getViewableBy($request->user());

        return view('admin.permissions.index')->with('permissions', $permissions);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit')->with([
            'permission' => $permission,
            'roles' => Role::orderBy('id')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $attributes = $this->validate($request, [
            'name' => 'required|string|max:250',
            'description' => 'required|string|max:250',
            'roles' => 'array',
        ]);

        $this->service->update($permission, $attributes);

        return redirect()->route('admin.permissions.index')
            ->with('status', trans('permission.updated'));
    }
}
