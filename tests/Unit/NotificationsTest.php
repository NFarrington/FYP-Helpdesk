<?php

namespace Tests\Unit;

use App\Models\Department;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketPost;
use App\Models\User;
use App\Notifications\EmailVerification;
use App\Notifications\LoginFailed;
use App\Notifications\LoginSuccessful;
use App\Notifications\Tickets\Assigned;
use App\Notifications\Tickets\Closed;
use App\Notifications\Tickets\Submitted;
use App\Notifications\Tickets\Transferred;
use App\Notifications\Tickets\WithAgent;
use App\Notifications\Tickets\WithCustomer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationsTest extends TestCase
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
     * Test the email verification notification.
     *
     * @return void
     */
    public function testEmailVerificationNotification()
    {
        $token = str_random(40);
        $notification = new EmailVerification($token);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);

        $this->assertContains('Verify Email Address', $mail->subject);
        $this->assertArraySubset(['old_email', 'new_email'], array_keys($db));
    }

    /**
     * Test the successful login notification.
     *
     * @return void
     */
    public function testLoginSuccessfulNotification()
    {
        $notification = new LoginSuccessful();
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);

        $this->assertContains('Successful Login Attempt', $mail->subject);
        $this->assertEmpty($db);
    }

    /**
     * Test the failed login notification.
     *
     * @return void
     */
    public function testLoginFailedNotification()
    {
        $notification = new LoginFailed();
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);

        $this->assertContains('Failed Login Attempt', $mail->subject);
        $this->assertEmpty($db);
    }

    /**
     * Test ticket assigned notification.
     *
     * @return void
     */
    public function testTicketAssignedNotification()
    {
        /** @var Ticket $ticket */
        $ticket = factory(Ticket::class)->create();
        $ticket->posts()->save(factory(TicketPost::class)->make());

        $notification = new Assigned($ticket);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);

        $this->assertContains('Ticket Assigned', $mail->subject);
        $this->assertArraySubset(['ticket_id', 'agent_id'], array_keys($db));
    }

    /**
     * Test ticket closed notification.
     *
     * @return void
     */
    public function testTicketClosedNotification()
    {
        $department = Department::first();

        /** @var Ticket $ticket */
        $ticket = factory(Ticket::class)->create(['department_id' => $department->id]);
        $ticket->posts()->save(factory(TicketPost::class)->make());

        $notification = new Closed($ticket);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);

        $this->assertContains('Ticket Closed', $mail->subject);
        $this->assertArraySubset(['ticket_id'], array_keys($db));

        /** @var User $agent */
        $agent = factory(User::class)->create();
        $agent->roles()->save(Role::agent());
        $agent->departments()->save($department);

        $mail = $notification->toMail($agent);
        $db = $notification->toArray($agent);

        $this->assertContains('Ticket Closed', $mail->subject);
        $this->assertArraySubset(['ticket_id'], array_keys($db));
    }

    /**
     * Test ticket submitted notification.
     *
     * @return void
     */
    public function testTicketSubmittedNotification()
    {
        /** @var Ticket $ticket */
        $ticket = factory(Ticket::class)->create();
        $ticket->posts()->save(factory(TicketPost::class)->make());

        $notification = new Submitted($ticket);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);

        $this->assertContains('New Ticket Submitted', $mail->subject);
        $this->assertArraySubset(['ticket_id'], array_keys($db));
    }

    /**
     * Test ticket transferred notification.
     *
     * @return void
     */
    public function testTicketTransferredNotification()
    {
        /** @var Ticket $ticket */
        $ticket = factory(Ticket::class)->create();
        $ticket->posts()->save(factory(TicketPost::class)->make());

        $notification = new Transferred($ticket);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);

        $this->assertContains('Ticket Transferred', $mail->subject);
        $this->assertArraySubset(['ticket_id', 'old_department', 'new_department'], array_keys($db));
    }

    /**
     * Test ticket with agent notification.
     *
     * @return void
     */
    public function testTicketWithAgentNotification()
    {
        /** @var Ticket $ticket */
        $ticket = factory(Ticket::class)->create();
        $ticket->posts()->save(factory(TicketPost::class)->make());

        $notification = new WithAgent($ticket);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);

        $this->assertContains('New Reply', $mail->subject);
        $this->assertArraySubset(['ticket_id'], array_keys($db));
    }

    /**
     * Test ticket with customer notification.
     *
     * @return void
     */
    public function testTicketWithCustomerNotification()
    {
        /** @var Ticket $ticket */
        $ticket = factory(Ticket::class)->create();
        $ticket->posts()->save(factory(TicketPost::class)->make());

        $notification = new WithCustomer($ticket);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);

        $this->assertContains('New Reply', $mail->subject);
        $this->assertArraySubset(['ticket_id'], array_keys($db));
    }
}
