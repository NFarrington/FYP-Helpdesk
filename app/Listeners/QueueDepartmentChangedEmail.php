<?php

namespace App\Listeners;

use App\Events\TicketUpdated;
use App\Notifications\Tickets\Transferred;

class QueueDepartmentChangedEmail
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

        if ($ticket->isDirty('department_id') && $ticket->agent_id === null) {
            foreach ($ticket->department->users as $user) {
                $user->notify(new Transferred($ticket));
            }
        }
    }
}
