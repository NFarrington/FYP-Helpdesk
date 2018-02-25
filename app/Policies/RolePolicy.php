<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

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
