<?php

namespace Tests\Feature\Staff;

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
        $this->user->departments()->attach(1);
    }

    /**
     * Test the ticket index loads successfully.
     *
     * @return void
     */
    public function testTicketIndexPageLoads()
    {
        $ticket1 = factory(Ticket::class)->states('open')->create(['department_id' => 1]);
        $ticket2 = factory(Ticket::class)->states('open')->create(['department_id' => 1, 'agent_id' => $this->user->id]);
        $response = $this->actingAs($this->user)->get(route('staff.tickets.index'));

        $response->assertStatus(200);
        $response->assertSeeText($ticket1->summary);
        $response->assertSeeText($ticket2->summary);
    }

    /**
     * Test the ticket index loads successfully.
     *
     * @return void
     */
    public function testTicketIndexPageRestrictsTickets()
    {
        $ticket = factory(Ticket::class)->states('open')->create(['department_id' => 2]);
        $response = $this->actingAs($this->user)->get(route('staff.tickets.index'));

        $response->assertStatus(200);
        $response->assertDontSeeText($ticket->summary);
    }

    /**
     * Test the closed ticket index loads successfully.
     *
     * @return void
     */
    public function testClosedTicketIndexPageLoads()
    {
        $ticket = factory(Ticket::class)->states('closed')->create(['department_id' => 1]);
        $response = $this->actingAs($this->user)->get(route('staff.tickets.index.closed'));

        $response->assertStatus(200);
        $response->assertSeeText($ticket->summary);
    }

    /**
     * Test the closed ticket index loads successfully.
     *
     * @return void
     */
    public function testClosedTicketIndexPageRestrictsTickets()
    {
        $ticket = factory(Ticket::class)->states('closed')->create(['department_id' => 2]);
        $response = $this->actingAs($this->user)->get(route('staff.tickets.index'));

        $response->assertStatus(200);
        $response->assertDontSeeText($ticket->summary);
    }
}
