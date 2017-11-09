<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\TicketDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketDepartmentTest extends TestCase
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
     * Test a department is linked to the tickets assigned to it.
     *
     * @return void
     */
    public function testTicketDepartmentHasTicket()
    {
        $department = factory(TicketDepartment::class)->create();
        $ticket = factory(Ticket::class)->create(['department_id' => $department->id]);

        $this->assertEquals($ticket->id, $department->tickets->first()->id);
    }

    /**
     * Test department scopes work correctly.
     *
     * @return void
     */
    public function testTicketStatusScopesCorrectly()
    {
        TicketDepartment::query()->delete();
        factory(TicketDepartment::class, 3)->states('internal')->create();
        factory(TicketDepartment::class, 3)->states('external')->create();

        $this->assertEquals(3, TicketDepartment::internal()->count());
        $this->assertEquals(3, TicketDepartment::external()->count());
    }
}
