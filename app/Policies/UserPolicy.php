<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function view(User $user, User $model = null)
    {
        $isAdmin = $user->hasRole(Role::admin());

        if ($model === null) {
            return $isAdmin;
        }

        return $user->id === $model->id || $isAdmin;
    }

    /**
     * Determine whether a user is an admin.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function admin(User $user)
    {
        return $user->hasRole(Role::admin());
    }

    /**
     * Determine whether a user is an agent.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function agent(User $user)
    {
        return $user->roles->isNotEmpty();
    }
}
