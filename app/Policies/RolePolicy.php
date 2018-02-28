<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User $user
     * @param Role|null $role
     * @return mixed
     */
    public function view(User $user, Role $role = null)
    {
        return $user->hasRole(Role::admin());
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User $user
     * @param Role|null $role
     * @return mixed
     */
    public function update(User $user, Role $role = null)
    {
        return $user->hasRole(Role::admin());
    }

    /**
     * Determine whether the user has the role.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return mixed
     */
    public function has(User $user, Role $role)
    {
        return $user->hasRole($role);
    }
}
