<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TicketDepartment;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketDepartmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can submit a ticket to the department.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TicketDepartment  $ticketDepartment
     * @return mixed
     */
    public function submitTicket(User $user, TicketDepartment $ticketDepartment)
    {
        return $ticketDepartment->internal === false;
    }
}
