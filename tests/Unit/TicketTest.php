<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\TicketPost;
use App\Models\TicketStatus;
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
        $ticketPost = factory(TicketPost::class)->create(['ticket_id' => $ticket->id]);

        $this->assertEquals($ticketPost->id, $ticket->posts->first()->id);
    }

    /**
     * Test a ticket has a status.
     *
     * @return void
     */
    public function testTicketHasStatus()
    {
        $status = TicketStatus::open()->first();

        $ticket = factory(Ticket::class)->create([
            'user_id' => $this->user->id,
            'status_id' => $status->id,
        ]);

        $this->assertEquals($status->id, $ticket->status->id);
    }

    /**
     * Test a status scopes work correctly.
     *
     * @return void
     */
    public function testTicketScopesCorrectly()
    {
        factory(Ticket::class, 3)->states('agent')->create();
        factory(Ticket::class, 3)->states('customer')->create();
        factory(Ticket::class, 3)->states('closed')->create();

        $this->assertEquals(3, Ticket::withAgent()->count());
        $this->assertEquals(3, Ticket::withCustomer()->count());
        $this->assertEquals(6, Ticket::open()->count());
        $this->assertEquals(3, Ticket::closed()->count());
    }
}
