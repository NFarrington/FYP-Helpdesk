<?php

namespace App\Services;

use App\Models\Department;
use App\Models\User;
use App\Repositories\DepartmentRepository;

class DepartmentService extends Service
{
    /**
     * The repository.
     *
     * @var DepartmentRepository
     */
    protected $repository;

    /**
     * Initialise the service.
     *
     * @param DepartmentRepository $repository
     */
    public function __construct(DepartmentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all model instances the user can view.
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function getViewableBy(User $user)
    {
        return $user->can('view', Department::class)
            ? $this->repository->getAll()
            : collect();
    }

    /**
     * Create a new department.
     *
     * @param array $attributes
     * @return \App\Models\Department
     */
    public function create(array $attributes)
    {
        $department = Department::create(array_only($attributes, ['name', 'description', 'internal']));
        $department->users()->sync(array_get($attributes, 'users'));

        return $department;
    }

    /**
     * Update a department.
     *
     * @param \App\Models\Department $department
     * @param array $attributes
     * @return \App\Models\Department
     */
    public function update(Department $department, array $attributes)
    {
        $department->fill(array_only($attributes, ['name', 'description', 'internal']));
        $department->users()->sync(array_get($attributes, 'users'));
        $department->save();

        return $department;
    }
}
