<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use App\Models\User;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * The service.
     *
     * @var DepartmentService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param DepartmentService $service
     */
    public function __construct(DepartmentService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $departments = $this->service->getViewableBy($request->user());

        return view('admin.departments.index')
            ->with('departments', $departments);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Department $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        return view('admin.departments.edit')->with([
            'department' => $department,
            'users' => User::has('roles')->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \App\Models\Department $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $attributes = $this->validate($request, [
            'name' => 'required|string|max:250',
            'description' => 'required|string|max:250',
            'internal' => 'required|boolean',
            'users' => 'array',
        ]);

        $this->service->update($department, $attributes);

        return redirect()->route('admin.departments.index')
            ->with('status', trans('department.updated'));
    }
}
