<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\RoleRepository;

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
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function getViewableBy(User $user)
    {
        return $user->can('view', Role::class)
            ? $this->repository->getAll()
            : collect();
    }

    /**
     * Create a role.
     *
     * @param array $attributes
     * @return \App\Models\Role
     */
    public function create(array $attributes)
    {
        $role = Role::create(array_only($attributes, ['key', 'name', 'description']));
        $role->permissions()->sync(array_get($attributes, 'permissions'));

        return $role;
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
