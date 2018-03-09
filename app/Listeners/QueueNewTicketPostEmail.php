<?php

namespace App\Listeners;

use App\Events\TicketPostCreated;
use App\Listeners\Concerns\QueuesTicketNotifications;
use App\Models\TicketStatus;
use Illuminate\Foundation\Application;

class QueueNewTicketPostEmail
{
    use QueuesTicketNotifications;

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
     * @param \App\Events\TicketPostCreated $event
     * @return void
     */
    public function handle(TicketPostCreated $event)
    {
        $ticket = $event->ticketPost->ticket;

        switch ($ticket->status->state) {
            case TicketStatus::STATUS_AGENT:
                $this->notifyAgentOrDepartment($ticket, new \App\Notifications\Agent\NewTicketPost($ticket));
                break;
            case TicketStatus::STATUS_CUSTOMER:
                $this->notifyCustomer($ticket, new \App\Notifications\User\NewTicketPost($ticket));
                break;
        }
    }
}
