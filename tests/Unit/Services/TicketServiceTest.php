<?php

namespace Tests\Unit\Services;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketPost;
use App\Models\TicketStatus;
use App\Models\User;
use App\Services\TicketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The service.
     *
     * @var TicketService
     */
    protected $service;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->service = $this->app->make(TicketService::class);
    }

    /**
     * Test the create method.
     *
     * @covers \App\Services\TicketService::create()
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function testCreate()
    {
        $template = factory(TicketPost::class)->make();
        $postArray = $template->attributesToArray();
        $ticketArray = $template->ticket->attributesToArray();
        $this->actingAs($template->user);

        $this->service->create($ticketArray + $postArray, $template->user);

        $this->assertDatabaseHas($template->ticket->getTable(), array_only($ticketArray, 'title', 'department_id'));
        $this->assertDatabaseHas($template->getTable(), array_only($postArray, 'content'));
    }

    /**
     * Test the update method.
     *
     * @covers \App\Services\TicketService::update()
     */
    public function testUpdate()
    {
        $ticket = factory(Ticket::class)->make();

        $this->service->update($ticket, ['close' => '1']);
        $this->assertEquals(TicketStatus::STATUS_CLOSED, $ticket->status->state);

        $this->service->update($ticket, ['open' => '1']);
        $this->assertEquals(TicketStatus::STATUS_AGENT, $ticket->status->state);
    }

    /**
     * Test the getTicketsByStatus method.
     *
     * @covers \App\Services\TicketService::getTicketsByStatus()
     * @throws \Exception
     */
    public function testGetTicketsByStatus()
    {
        $user = factory(User::class)->create();
        factory(Ticket::class)->states('open')->create(['user_id' => $user->id]);
        factory(Ticket::class)->states('closed')->create(['user_id' => $user->id]);

        $tickets = $this->service->getTicketsByStatus($user);

        $this->assertEquals(1, $tickets['open']->count());
        $this->assertEquals(1, $tickets['closed']->count());
    }

    /**
     * Test the getSubmittableDepartments method.
     *
     * @covers \App\Services\TicketService::getSubmittableDepartments()
     */
    public function testGetSubmittableDepartments()
    {
        Department::query()->delete();
        factory(Department::class)->states('internal')->create();
        factory(Department::class)->states('external')->create();

        $user = mock(User::class);
        $user->allows(['can' => true]);
        $viewable = $this->service->getSubmittableDepartments($user);
        $this->assertEquals(2, $viewable->count());

        $user = mock(User::class);
        $user->allows(['can' => false]);
        $viewable = $this->service->getSubmittableDepartments($user);
        $this->assertEquals(1, $viewable->count());
    }

    /**
     * Test the getViewableBy method.
     *
     * @covers \App\Services\TicketService::getViewableBy()
     */
    public function testGetViewableBy()
    {
        $user = factory(User::class)->create();
        factory(Ticket::class)->create(['user_id' => $user->id]);
        factory(Ticket::class)->create();

        $viewable = $this->service->getViewableBy($user);
        $this->assertEquals(1, count($viewable->items()));

        $mockUser = mock(User::class);
        $mockUser->allows()->can('view', Ticket::class)->andReturns(true);
        $mockUser->allows()->getAttribute('id')->andReturns(mt_rand(100000, 999999));
        $mockUser->allows()->getAttribute('departments')->andReturns(Department::all());
        $viewable = $this->service->getViewableBy($mockUser);
        $this->assertEquals(2, count($viewable->items()));
    }
}
