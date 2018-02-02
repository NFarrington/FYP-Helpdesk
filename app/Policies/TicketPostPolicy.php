<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\TicketPost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the ticketPost.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TicketPost  $ticketPost
     * @return mixed
     */
    public function update(User $user, TicketPost $ticketPost)
    {
        return $user->hasRole(Role::agent()) &&
            $user->hasPermission('tickets.posts.update') &&
            $user->can('update-as-agent', $ticketPost->ticket);
    }

    /**
     * Determine whether the user can delete the ticketPost.
     *
     * @codeCoverageIgnore
     * @todo    implement
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TicketPost  $ticketPost
     * @return mixed
     */
    public function delete(User $user, TicketPost $ticketPost)
    {
        return true;
    }
}
