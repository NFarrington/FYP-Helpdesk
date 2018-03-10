<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Listeners\Concerns\QueuesTicketNotifications;
use App\Notifications\Agent\TicketSubmitted;

class QueueTicketSubmittedEmail
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
     * @param \App\Events\TicketCreated $event
     * @return void
     */
    public function handle(TicketCreated $event)
    {
        $ticket = $event->ticket;

        $this->notifyAgentOrDepartment($ticket, new TicketSubmitted($ticket));
    }
}
