<?php

namespace App\Listeners;

use App\Events\TicketUpdated;
use App\Notifications\Tickets\Assigned;

class QueueTicketAssignmentEmail
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

        if ($ticket->isDirty('agent_id')) {
            $ticket->agent->notify(new Assigned($ticket));
        }
    }
}
