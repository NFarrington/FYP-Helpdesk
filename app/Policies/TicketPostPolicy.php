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
     * @codeCoverageIgnore
     * @todo    implement
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
