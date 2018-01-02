<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentTest extends TestCase
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
    public function testDepartmentHasTicket()
    {
        $department = factory(Department::class)->create();
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
        Department::query()->delete();
        factory(Department::class, 3)->states('internal')->create();
        factory(Department::class, 3)->states('external')->create();

        $this->assertEquals(3, Department::internal()->count());
        $this->assertEquals(3, Department::external()->count());
    }
}
