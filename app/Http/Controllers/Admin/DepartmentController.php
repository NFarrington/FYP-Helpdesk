<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use App\Models\User;
use App\Services\DepartmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

        return view('admin.departments.index')->with([
            'departments' => $this->paginate($departments, 20, ['path' => route('admin.departments.index')]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.departments.create')->with([
            'department' => new Department(),
            'users' => User::has('roles')->orderBy('name')->get(),
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
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:250',
            'internal' => 'required|boolean',
            'users' => 'array',
        ]);

        $this->service->create($attributes);

        return redirect()->route('admin.departments.index')
            ->with('status', trans('department.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param Department $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        Session::reflash();

        return redirect()->route('admin.departments.edit', $department);
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
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:250',
            'internal' => 'required|boolean',
            'users' => 'array',
        ]);

        $this->service->update($department, $attributes);

        return redirect()->route('admin.departments.index')
            ->with('status', trans('department.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Department $department
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Department $department)
    {
        if ($department->tickets()->count() > 0) {
            return redirect()->route('admin.departments.index')
                ->with('error', trans('department.not-deleted.tickets'));
        }

        $department->users()->sync([]);
        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('status', trans('department.deleted'));
    }
}
