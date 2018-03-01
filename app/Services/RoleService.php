<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\RoleRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleService extends Service
{
    /**
     * The repository.
     *
     * @var RoleRepository
     */
    protected $repository;

    /**
     * Initialise the service.
     *
     * @param RoleRepository $repository
     */
    public function __construct(RoleRepository $repository)
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
        $roles = $user->can('view', Role::class)
            ? $this->repository->getAll()
            : collect();

        return new LengthAwarePaginator($roles, $roles->count(), 20);
    }

    /**
     * Update a role.
     *
     * @param Role $role
     * @param array $attributes
     * @return Role
     */
    public function update(Role $role, array $attributes)
    {
        $role->fill(array_only($attributes, ['name', 'description']));
        $role->permissions()->sync(array_get($attributes, 'permissions'));
        $role->save();

        return $role;
    }
}
