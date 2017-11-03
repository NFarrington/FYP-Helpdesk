<?php

namespace Tests\Unit;

use App\Models\Ticket;
use App\Models\TicketPost;
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
}
