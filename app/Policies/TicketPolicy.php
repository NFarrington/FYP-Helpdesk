<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the ticket.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ticket  $ticket
     * @return mixed
     */
    public function view(User $user, Ticket $ticket)
    {
        return true;
    }

    /**
     * Determine whether the user can create tickets.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the ticket.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ticket  $ticket
     * @return mixed
     */
    public function update(User $user, Ticket $ticket)
    {
        return true;
    }

//    /**
//     * Determine whether the user can delete the ticket.
//     *
//     * @param  \App\Models\User  $user
//     * @param  \App\Models\Ticket  $ticket
//     * @return mixed
//     */
//    public function delete(User $user, Ticket $ticket)
//    {
//        return true;
//    }
}
