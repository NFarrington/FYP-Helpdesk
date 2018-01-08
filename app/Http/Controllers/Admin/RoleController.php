<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.roles.index')->with('roles', Role::orderBy('id')->paginate(20));
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
        $this->validate($request, [
            'name' => 'required|string|max:250',
            'description' => 'required|string|max:250',
            'permissions' => 'array',
        ]);

        $role->fill($request->only('name', 'description'));
        $role->permissions()->sync($request->input('permissions'));
        $role->save();

        return redirect(route('admin.roles.index'))->with('status', trans('role.updated'));
    }
}
