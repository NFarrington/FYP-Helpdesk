<?php

namespace Tests\Feature\Agent;

use App\Models\Permission;
use App\Models\Role;
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
        $this->user->departments()->attach(1);
        $this->user->roles()->attach(Role::agent()->id);
        Role::agent()->permissions()->sync(Permission::pluck('id'));
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
        $response = $this->actingAs($this->user)->get(route('agent.tickets.index'));

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
        $response = $this->actingAs($this->user)->get(route('agent.tickets.index'));

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
        $response = $this->actingAs($this->user)->get(route('agent.tickets.index.closed'));

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
        $response = $this->actingAs($this->user)->get(route('agent.tickets.index'));

        $response->assertStatus(200);
        $response->assertDontSeeText($ticket->summary);
    }

    /**
     * Test a submitted ticket can be viewed.
     *
     * @return void
     */
    public function testTicketsCanBeViewed()
    {
        $ticket = factory(Ticket::class)->create(['department_id' => 1]);

        $response = $this->actingAs($this->user)->get(route('agent.tickets.show', $ticket));

        $response->assertStatus(200);
    }

    /**
     * Test a submitted ticket can be updated.
     *
     * @return void
     */
    public function testTicketsCanBeUpdated()
    {
        $ticket = factory(Ticket::class)->states('open')->create(['department_id' => 1]);
        $this->actingAs($this->user);

        $this->get(route('agent.tickets.show', $ticket));

        $department = 2;
        $status = TicketStatus::closed()->first()->id;
        $response = $this->put(route('agent.tickets.update', $ticket), [
            'department' => $department,
            'status' => $status,
        ]);

        $response->assertRedirect(route('agent.tickets.index'));

        $ticket = $ticket->fresh();
        $this->assertEquals($department, $ticket->department->id);
        $this->assertEquals($status, $ticket->status->id);
    }

    /**
     * Test a submitted ticket can be reassigned.
     *
     * @return void
     */
    public function testTicketsCanBeReassigned()
    {
        $ticket = factory(Ticket::class)->states('open')->create(['department_id' => 1]);
        $this->actingAs($this->user);

        $this->get(route('agent.tickets.show', $ticket));

        $department = 1;
        $status = TicketStatus::closed()->first()->id;
        $response = $this->put(route('agent.tickets.update', $ticket), [
            'department' => $department,
            'status' => $status,
            'agent' => $this->user->id,
        ]);

        $response->assertRedirect(route('agent.tickets.show', $ticket));

        $ticket = $ticket->fresh();
        $this->assertEquals($department, $ticket->department->id);
        $this->assertEquals($status, $ticket->status->id);
        $this->assertEquals($this->user->id, $ticket->agent->id);
    }

    /**
     * Test a ticket's post can be updated.
     *
     * @return void
     */
    public function testTicketPostCanBeUpdated()
    {
        $ticket = factory(Ticket::class)->states('open')->create(['department_id' => 1]);
        $post = factory(TicketPost::class)->create(['ticket_id' => $ticket->id]);
        $this->actingAs($this->user);

        $this->get(route('agent.tickets.show', $ticket));

        $content = str_random();
        $response = $this->put(route('tickets.posts.update', [$ticket, $post]), [
            'content' => $content,
        ]);

        $response->assertRedirect(route('agent.tickets.show', $ticket));

        $post = $post->fresh();
        $this->assertEquals($content, $post->content);
    }

    /**
     * Test a ticket's post can be updated.
     *
     * @return void
     */
    public function testTicketPostCanBeDeleted()
    {
        $ticket = factory(Ticket::class)->states('open')->create(['department_id' => 1]);
        $post = factory(TicketPost::class)->create(['ticket_id' => $ticket->id]);
        $this->actingAs($this->user);

        $this->get(route('agent.tickets.show', $ticket));

        $response = $this->delete(route('tickets.posts.update', [$ticket, $post]));

        $response->assertRedirect(route('agent.tickets.show', $ticket));

        $this->assertDatabaseMissing($post->getTable(), ['id' => $post->id]);
    }

    /**
     * Test updating a ticket redirects to it if permitted.
     *
     * @return void
     */
    public function testUpdatedTicketRedirectsCorrectly()
    {
        $ticket = factory(Ticket::class)->states('open')->create(['department_id' => 1]);
        $this->actingAs($this->user);

        $this->get(route('agent.tickets.show', $ticket));
        $response = $this->put(route('agent.tickets.update', $ticket), [
            'department' => $ticket->department_id,
            'status' => $ticket->department_id,
        ]);

        $response->assertRedirect(route('agent.tickets.show', $ticket));
    }
}
