<?php

namespace App\Listeners\Concerns;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

trait QueuesTicketNotifications
{
    /**
     * Notify all agents in the ticket's department.
     *
     * @param \App\Models\Ticket $ticket
     * @param \App\Notifications\Notification $notification
     * @return \App\Models\User[]|\Illuminate\Support\Collection
     */
    protected function notifyDepartment(Ticket $ticket, Notification $notification)
    {
        return $ticket->department->users
            ->filter(function (User $user) {
                return !match(Auth::user(), $user);
            })->map(function (User $user) use ($notification) {
                return tap($user)->notify($notification);
            });
    }

    /**
     * Notify only the ticket's assigned agent.
     *
     * @param \App\Models\Ticket $ticket
     * @param \App\Notifications\Notification $notification
     * @return \App\Models\User|null
     */
    protected function notifyAgent(Ticket $ticket, Notification $notification)
    {
        if (!$ticket->agent) {
            return null;
        }

        $shouldNotify = !match(Auth::user(), $ticket->agent);

        return tap_if($shouldNotify, $ticket->agent, function (User $agent) use ($notification) {
            $agent->notify($notification);
        });
    }

    /**
     * Notify a ticket's agent if it has one, otherwise notify the department.
     *
     * @param \App\Models\Ticket $ticket
     * @param \App\Notifications\Notification $notification
     * @return \App\Models\User[]|\Illuminate\Support\Collection|null
     */
    protected function notifyAgentOrDepartment(Ticket $ticket, Notification $notification)
    {
        if (!$agent = $this->notifyAgent($ticket, $notification)) {
            return $this->notifyDepartment($ticket, $notification);
        }

        return collect($agent);
    }

    /**
     * Notify the customer.
     *
     * @param \App\Models\Ticket $ticket
     * @param \App\Notifications\Notification $notification
     * @return \App\Models\User|null
     */
    protected function notifyCustomer(Ticket $ticket, Notification $notification)
    {
        if (match(Auth::user(), $ticket->user) === true) {
            return null;
        }

        return tap($ticket->user)->notify($notification);
    }
}
