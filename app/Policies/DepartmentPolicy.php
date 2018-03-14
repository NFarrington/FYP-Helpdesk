<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the permission.
     *
     * @param  \App\Models\User $user
     * @param \App\Models\Department $department
     * @return mixed
     */
    public function view(User $user, Department $department = null)
    {
        return $user->hasRole(Role::admin());
    }

    /**
     * Determine whether the user can submit a ticket to the department.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Department  $department
     * @return mixed
     */
    public function submitTicket(User $user, Department $department)
    {
        return $department->internal === false;
    }
}
