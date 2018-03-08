<?php

namespace App\Listeners;

use App\Events\TicketUpdated;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Notifications\Tickets\Closed;
use App\Notifications\Tickets\WithAgent;
use App\Notifications\Tickets\WithCustomer;
use Illuminate\Foundation\Application;

class QueueStatusChangedEmail
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Create the event listener.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\TicketUpdated $event
     * @return void
     */
    public function handle(TicketUpdated $event)
    {
        $ticket = $event->ticket;

        if ($ticket->isDirty('status_id')) {
            switch ($ticket->status->state) {
                case TicketStatus::STATUS_AGENT:
                    $this->notifyAgents($ticket, new WithAgent($ticket));
                    break;
                case TicketStatus::STATUS_CUSTOMER:
                    $this->notifyCustomer($ticket, new WithCustomer($ticket));
                    break;
                case TicketStatus::STATUS_CLOSED:
                    $this->notifyCustomer($ticket, new Closed($ticket, 'user'));
                    $this->notifyAssignedAgent($ticket, new Closed($ticket, 'agent'));
                    break;
            }
        }
    }

    protected function notifyAgents(Ticket $ticket, $notification)
    {
        foreach ($ticket->department->users as $user) {
            $user->notify($notification);
        }
    }

    protected function notifyAssignedAgent(Ticket $ticket, $notification)
    {
        $agent = $ticket->agent;
        if ($agent) {
            $agent->notify($notification);
        }
    }

    protected function notifyCustomer(Ticket $ticket, $notification)
    {
        $ticket->user->notify($notification);
    }
}
