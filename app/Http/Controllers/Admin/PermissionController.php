<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.permissions.index')->with('permissions', Permission::orderBy('key')->paginate(20));
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
        $this->validate($request, [
            'name' => 'required|string|max:250',
            'description' => 'required|string|max:250',
            'roles' => 'array',
        ]);

        $permission->fill($request->only('name', 'description'));
        $permission->roles()->sync($request->input('roles'));
        $permission->save();

        return redirect(route('admin.permissions.index'))->with('status', trans('permission.updated'));
    }
}
