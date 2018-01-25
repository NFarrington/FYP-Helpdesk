<?php

namespace Tests\Unit;

use App\Models\Department;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketPost;
use App\Models\TicketStatus;
use App\Models\User;
use App\Notifications\Tickets\Assigned;
use App\Notifications\Tickets\Closed;
use App\Notifications\Tickets\WithAgent;
use App\Notifications\Tickets\WithCustomer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
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
     * Test a ticket is linked to the department it is assigned to.
     *
     * @return void
     */
    public function testTicketHasDepartment()
    {
        $department = factory(Department::class)->create();
        $ticket = factory(Ticket::class)->create(['department_id' => $department->id]);

        $this->assertEquals($department->id, $ticket->department->id);
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

    public function testTicketStatusChangeNotification()
    {
        $department = Department::first();

        /** @var User $agent */
        $agent = factory(User::class)->create();
        $agent->roles()->save(Role::agent());
        $agent->departments()->save($department);

        /** @var Ticket $ticket */
        $ticket = factory(Ticket::class)->states('customer')->create([
            'user_id' => $this->user->id,
            'department_id' => $department->id,
        ]);
        $ticket->status()->associate(TicketStatus::withAgent()->first());
        $ticket->save();
        Notification::assertSentTo($agent, WithAgent::class);

        /** @var Ticket $ticket */
        $ticket = factory(Ticket::class)->states('agent')->create(['user_id' => $this->user->id]);
        $ticket->status()->associate(TicketStatus::withCustomer()->first());
        $ticket->save();
        Notification::assertSentTo($this->user, WithCustomer::class);

        /** @var Ticket $ticket */
        $ticket = factory(Ticket::class)->states('agent')->create([
            'user_id' => $this->user->id,
            'agent_id' => $agent->id,
            'department_id' => $department->id,
        ]);
        $ticket->status()->associate(TicketStatus::closed()->first());
        $ticket->save();
        Notification::assertSentTo($this->user, Closed::class);
        Notification::assertSentTo($agent, Closed::class);

        /** @var Ticket $ticket */
        $ticket = factory(Ticket::class)->states('agent')->create(['user_id' => $this->user->id]);
        $ticket->agent()->associate($agent);
        $ticket->save();
        Notification::assertSentTo($agent, Assigned::class);
    }
}
