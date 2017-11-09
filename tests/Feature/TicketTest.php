<?php

namespace Tests\Feature;

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
     * Test the ticket index loads successfully.
     *
     * @return void
     */
    public function testTicketIndexPageLoads()
    {
        $openTicket = factory(Ticket::class)->states('open')->create(['user_id' => $this->user->id]);
        $closedTicket = factory(Ticket::class)->states('closed')->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user)->get(route('tickets.index'));

        $response->assertStatus(200);
        $response->assertSeeText($openTicket->summary);
        $response->assertSeeText($closedTicket->summary);
    }

    /**
     * Test the ticket submission page loads successfully.
     *
     * @return void
     */
    public function testTicketSubmissionPageLoads()
    {
        $response = $this->actingAs($this->user)->get(route('tickets.create'));

        $response->assertStatus(200);
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

        $this->actingAs($this->user);
        $this->get(route('tickets.create'));
        $response = $this->post(route('tickets.store'), [
            'department' => $ticket->department_id,
            'summary' => $ticket->summary,
            'content' => $ticketPost->content,
        ]);

        $response->assertRedirect(route('tickets.show', $ticket->id + 1));
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
        $ticket = factory(Ticket::class)->create();

        $response = $this->actingAs($this->user)->get(route('tickets.show', $ticket->id));

        $response->assertStatus(200);
    }

    /**
     * Test a submitted ticket can be viewed.
     *
     * @return void
     */
    public function testTicketsCanBeRepliedTo()
    {
        $ticket = factory(Ticket::class)->create(['user_id' => $this->user->id]);
        $ticketPost = factory(TicketPost::class)->make();
        $this->actingAs($this->user);

        $this->get(route('tickets.show', $ticket->id));
        $response = $this->post(route('posts.store', $ticket->id), [
            'reply' => $ticketPost->content,
        ]);

        $response->assertRedirect(route('tickets.show', $ticket->id));
        $this->assertDatabaseHas($ticketPost->getTable(), ['content' => $ticketPost->content]);
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

        $this->get(route('tickets.show', $ticket->id));
        $response = $this->put(route('tickets.update', $ticket->id), [
            'close' => 'true',
        ]);

        $response->assertRedirect(route('tickets.index'));

        $ticket = $ticket->fresh();
        $this->assertEquals(TicketStatus::STATUS_CLOSED, $ticket->status->state);
    }
}
