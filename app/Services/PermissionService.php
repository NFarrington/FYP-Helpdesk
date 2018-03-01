<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\User;
use App\Repositories\PermissionRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class PermissionService extends Service
{
    /**
     * The repository.
     *
     * @var PermissionRepository
     */
    protected $repository;

    /**
     * Initialise the service.
     *
     * @param PermissionRepository $repository
     */
    public function __construct(PermissionRepository $repository)
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
        $permissions = $user->can('view', Permission::class)
            ? $this->repository->getAll()
            : collect();

        return new LengthAwarePaginator($permissions, $permissions->count(), 20);
    }

    /**
     * Update a permission.
     *
     * @param Permission $permission
     * @param array $attributes
     * @return Permission
     */
    public function update(Permission $permission, array $attributes)
    {
        $permission->fill(array_only($attributes, ['name', 'description']));
        $permission->roles()->sync(array_get($attributes, 'roles'));
        $permission->save();

        return $permission;
    }
}
