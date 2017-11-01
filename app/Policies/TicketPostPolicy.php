<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TicketPost;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the ticketPost.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TicketPost  $ticketPost
     * @return mixed
     */
    public function view(User $user, TicketPost $ticketPost)
    {
        return true;
    }

    /**
     * Determine whether the user can create ticketPosts.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the ticketPost.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TicketPost  $ticketPost
     * @return mixed
     */
    public function update(User $user, TicketPost $ticketPost)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the ticketPost.
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
