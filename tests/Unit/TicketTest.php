<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
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
     * Test a ticket is linked to the user who submitted it.
     *
     * @return void
     */
    public function testTicketHasUser()
    {
        $ticket = factory(Ticket::class)->create(['user_id' => $this->user->id]);

        $this->assertEquals($this->user->id, $ticket->user->id);
    }

    /**
     * Test a ticket is linked to its posts.
     *
     * @return void
     */
    public function testTicketHasPosts()
    {
        $ticket = factory(Ticket::class)->create(['user_id' => $this->user->id]);
        $ticketPost = factory(Ticket::class)->create(['ticket_id' => $ticket->id]);

        $this->assertEquals($ticketPost->id, $ticket->posts->first()->id);
    }
}
