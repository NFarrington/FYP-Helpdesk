<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\TicketPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketPostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test user.
     *
     * @var User
     */
    protected $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * Test a ticket post is linked to the user who submitted it.
     *
     * @return void
     */
    public function testTicketPostHasUser()
    {
        $ticket = factory(TicketPost::class)->create(['user_id' => $this->user->id]);

        $this->assertEquals($this->user->id, $ticket->user->id);
    }

    /**
     * Test a ticket post is linked to the parent ticket.
     *
     * @return void
     */
    public function testTicketPostHasTicket()
    {
        $ticket = factory(Ticket::class)->create();
        $ticketPost = factory(TicketPost::class)->create(['ticket_id' => $ticket->id]);

        $this->assertEquals($ticket->id, $ticketPost->ticket->id);
    }
}
