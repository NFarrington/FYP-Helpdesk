<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketStatusTest extends TestCase
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
     * Test a ticket status is linked to tickets.
     *
     * @return void
     */
    public function testTicketStatusHasTickets()
    {
        $status = TicketStatus::open()->first();

        factory(Ticket::class)->create([
            'user_id' => $this->user->id,
            'status_id' => $status->id,
        ]);

        $this->assertNotEmpty($status->tickets);
    }

    /**
     * Test a ticket status is linked to tickets.
     *
     * @return void
     */
    public function testTicketStatusScopesCorrectly()
    {
        TicketStatus::query()->delete();
        factory(TicketStatus::class, 3)->states('agent')->create();
        factory(TicketStatus::class, 3)->states('customer')->create();
        factory(TicketStatus::class, 3)->states('closed')->create();

        $this->assertEquals(3, TicketStatus::withAgent()->count());
        $this->assertEquals(3, TicketStatus::withCustomer()->count());
        $this->assertEquals(6, TicketStatus::open()->count());
        $this->assertEquals(3, TicketStatus::closed()->count());
    }

    /**
     * Test the isOpen() method.
     *
     * @return void
     * @covers \App\Models\TicketStatus::isOpen()
     */
    public function testIsOpen()
    {
        $open = factory(TicketStatus::class)->states('open')->make();

        $this->assertTrue($open->isOpen());
        $this->assertFalse($open->isClosed());
    }

    /**
     * Test the isClosed() method.
     *
     * @return void
     * @covers \App\Models\TicketStatus::isClosed()
     */
    public function testIsClosed()
    {
        $closed = factory(TicketStatus::class)->states('closed')->make();

        $this->assertTrue($closed->isClosed());
        $this->assertFalse($closed->isOpen());
    }
}
