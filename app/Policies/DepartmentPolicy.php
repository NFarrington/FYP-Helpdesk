<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can submit a ticket to the department.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Department  $ticketDepartment
     * @return mixed
     */
    public function submitTicket(User $user, Department $ticketDepartment)
    {
        return $ticketDepartment->internal === false;
    }
}
