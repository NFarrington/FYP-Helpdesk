<?php

namespace App\Listeners;

use App\Events\TicketUpdated;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Notifications\Tickets\Closed;
use App\Notifications\Tickets\WithAgent;
use App\Notifications\Tickets\WithCustomer;

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
     * @return void
     */
    public function __construct(\Illuminate\Foundation\Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle the event.
     *
     * @return void
     * @throws \Exception
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
                    $this->notifyCustomer($ticket, new Closed($ticket));
                    $this->notifyAssignedAgent($ticket, new Closed($ticket));
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
