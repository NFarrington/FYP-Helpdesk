<?php

namespace Tests\Feature\Api;

use App\Models\Ticket;
use App\Models\TicketPost;
use App\Models\TicketStatus;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        Passport::actingAs($this->user);
    }

    /**
     * Test the ticket index loads successfully.
     *
     * @return void
     */
    public function testTicketIndexPageLoads()
    {
        $openTicket = factory(Ticket::class)->states('open')->create(['user_id' => $this->user->id]);
        $closedTicket = factory(Ticket::class)->states('closed')->create(['user_id' => $this->user->id]);
        $response = $this->get(route('api.tickets.index'));

        $response->assertStatus(200);
        $response->assertSeeText($openTicket->summary);
        $response->assertSeeText($closedTicket->summary);
    }

    /**
     * Test tickets can be submitted successfully.
     *
     * @return void
     */
    public function testTicketSubmitsSuccessfully()
    {
        $ticketPost = factory(TicketPost::class)->make();
        $ticket = $ticketPost->ticket;

        $response = $this->post(route('api.tickets.store'), [
            'department_id' => $ticket->department_id,
            'summary' => $ticket->summary,
            'content' => $ticketPost->content,
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas($ticket->getTable(), [
            'user_id' => $this->user->id,
            'summary' => $ticket->summary,
        ]);
        $this->assertDatabaseHas($ticketPost->getTable(), [
            'user_id' => $this->user->id,
            'content' => $ticketPost->content,
        ]);
    }

    /**
     * Test a submitted ticket can be viewed.
     *
     * @return void
     */
    public function testTicketsCanBeViewed()
    {
        $ticket = factory(Ticket::class)->create(['user_id' => $this->user->id]);

        $response = $this->get(route('api.tickets.show', $ticket->id));

        $response->assertStatus(200);
    }

    /**
     * Test a submitted ticket can be closed.
     *
     * @return void
     */
    public function testTicketsCanBeClosed()
    {
        $ticket = factory(Ticket::class)->create(['user_id' => $this->user->id]);
        $this->actingAs($this->user);

        $response = $this->put(route('api.tickets.update', $ticket->id), [
            'close' => '1',
        ]);

        $response->assertSuccessful();

        $ticket = $ticket->fresh();
        $this->assertEquals(TicketStatus::STATUS_CLOSED, $ticket->status->state);
    }
}
