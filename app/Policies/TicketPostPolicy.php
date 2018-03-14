<?php

namespace App\Policies;

use App\Models\TicketPost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the ticketPost.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\TicketPost $ticketPost
     * @return mixed
     */
    public function update(User $user, TicketPost $ticketPost)
    {
        return $user->hasPermission('tickets.posts.update') &&
            $user->can('update-as-agent', $ticketPost->ticket);
    }

    /**
     * Determine whether the user can delete the ticketPost.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\TicketPost $ticketPost
     * @return mixed
     */
    public function delete(User $user, TicketPost $ticketPost)
    {
        return $user->hasPermission('tickets.posts.delete') &&
            $user->can('update-as-agent', $ticketPost->ticket);
    }
}
