<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;

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
     */
    public function index(Request $request)
    {
        $roles = $this->service->getViewableBy($request->user());

        return view('admin.roles.index')->with('roles', $roles);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit')->with([
            'role' => $role,
            'permissions' => Permission::orderBy('key')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role   $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $attributes = $this->validate($request, [
            'name' => 'required|string|max:250',
            'description' => 'required|string|max:250',
            'permissions' => 'array',
        ]);

        $this->service->update($role, $attributes);

        return redirect()->route('admin.roles.index')->with('status', trans('role.updated'));
    }
}
