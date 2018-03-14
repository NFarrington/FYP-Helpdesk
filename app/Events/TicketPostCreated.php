<?php

namespace App\Events;

use App\Models\TicketPost;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketPostCreated
{
    use Dispatchable, SerializesModels;

    /**
     * The ticket post that has been created.
     *
     * @var TicketPost
     */
    public $ticketPost;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\TicketPost $ticketPost
     * @return void
     */
    public function __construct(TicketPost $ticketPost)
    {
        $this->ticketPost = $ticketPost;
    }
}
