<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\TicketPost;
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
        $ticket = factory(Ticket::class)->make();
        $ticketPost = factory(TicketPost::class)->make();

        $this->actingAs($this->user);
        $this->get(route('tickets.create'));
        $response = $this->post(route('tickets.store'), [
            'summary' => $ticket->summary,
            'description' => $ticketPost->content,
        ]);

        $response->assertRedirect(route('tickets.show'));
        $this->assertDatabaseHas((new Ticket())->getTable(), [
            'user_id' => $this->user->id,
            'summary' => $ticketPost->summary,
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
        $ticket = factory(Ticket::class)->create(['submitter_id' => $this->user->id]);
        $ticketPost = factory(TicketPost::class)->make();
        $this->actingAs($this->user);

        $this->get(route('tickets.show', $ticket->id));
        $response = $this->put(route('tickets.update', $ticket->id), [
            'reply' => $ticketPost->content,
        ]);

        $response->assertRedirect(route('tickets.show', $ticket->id));
        $this->assertDatabaseHas($ticketPost->getTable(), ['content' => $ticketPost->content]);
    }
}
