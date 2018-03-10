<?php

namespace App\Listeners;

use App\Events\TicketUpdated;
use App\Listeners\Concerns\QueuesTicketNotifications;
use App\Notifications\Agent\TicketAssigned;

class QueueTicketAssignmentEmail
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
     * @return void
     */
    public function __construct(\Illuminate\Foundation\Application $app)
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

        if ($ticket->isDirty('agent_id')) {
            $this->notifyAgent($ticket, new TicketAssigned($ticket));
        }
    }
}
