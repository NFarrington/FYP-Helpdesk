<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Notifications\Tickets\Submitted;

class QueueTicketSubmittedEmail
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
    public function handle(TicketCreated $event)
    {
        $ticket = $event->ticket;

        foreach ($ticket->department->users as $user) {
            $user->notify(new Submitted($ticket));
        }
    }
}
