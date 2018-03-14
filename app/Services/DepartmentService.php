<?php

namespace App\Services;

use App\Models\Department;
use App\Models\User;
use App\Repositories\DepartmentRepository;
use Illuminate\Pagination\LengthAwarePaginator;

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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|LengthAwarePaginator
     */
    public function getViewableBy(User $user)
    {
        $permissions = $user->can('view', Department::class)
            ? $this->repository->getAll()
            : collect();

        return new LengthAwarePaginator($permissions, $permissions->count(), 20);
    }

    /**
     * Update a permission.
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
