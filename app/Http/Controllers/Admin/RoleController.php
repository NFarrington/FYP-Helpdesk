<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

        return view('admin.roles.index')->with([
            'roles' => $this->paginate($roles, 20, ['path' => route('admin.roles.index')]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.roles.create')->with([
            'role' => new Role(),
            'permissions' => Permission::query()->orderBy('key')->get(),
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
            'key' => 'required|string|max:50|unique:roles,key',
            'name' => 'required|string|max:100|unique:roles,name',
            'description' => 'required|string|max:250',
            'permissions' => 'array',
        ]);

        $this->service->create($attributes);

        return redirect()->route('admin.roles.index')
            ->with('status', trans('role.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        Session::reflash();

        return redirect()->route('admin.roles.edit', $role);
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
            'permissions' => Permission::query()->orderBy('key')->get(),
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
            'name' => "required|string|max:100|unique:roles,name,{$role->id}",
            'description' => 'required|string|max:250',
            'permissions' => 'array',
        ]);

        $this->service->update($role, $attributes);

        return redirect()->route('admin.roles.index')->with('status', trans('role.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        $role->users()->sync([]);
        $role->permissions()->sync([]);
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('status', trans('role.deleted'));
    }
}
