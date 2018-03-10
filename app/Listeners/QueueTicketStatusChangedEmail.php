<?php

namespace App\Listeners;

use App\Events\TicketUpdated;
use App\Listeners\Concerns\QueuesTicketNotifications;
use App\Models\TicketStatus;
use Illuminate\Foundation\Application;

class QueueTicketStatusChangedEmail
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
     * @param \App\Events\TicketUpdated $event
     * @return void
     */
    public function handle(TicketUpdated $event)
    {
        $ticket = $event->ticket;

        if ($ticket->isDirty('status_id')) {
            switch ($ticket->status->state) {
                case TicketStatus::STATUS_CLOSED:
                    $this->notifyAgentOrDepartment($ticket, new \App\Notifications\Agent\TicketClosed($ticket));
                    $this->notifyCustomer($ticket, new \App\Notifications\User\TicketClosed($ticket));
            }
        }
    }
}
