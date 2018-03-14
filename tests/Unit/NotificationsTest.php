<?php

namespace Tests\Unit;

use App\Models\Department;
use App\Models\Role;
use App\Models\SlackWebhook;
use App\Models\Ticket;
use App\Models\TicketPost;
use App\Models\User;
use App\Notifications\Agent\TicketAssigned;
use App\Notifications\Agent\TicketSubmitted;
use App\Notifications\Agent\TicketTransferred;
use App\Notifications\LoginFailed;
use App\Notifications\LoginSuccessful;
use App\Notifications\VerifyEmail;
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
     * A Slack webhook.
     *
     * @var \App\Models\SlackWebhook
     */
    protected $webhook;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->webhook = factory(SlackWebhook::class)->create(['user_id' => $this->user]);
    }

    /**
     * Test the email verification notification.
     *
     * @return void
     */
    public function testEmailVerificationNotification()
    {
        $token = str_random(40);
        $notification = new VerifyEmail($token);
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
        $notification->setSlackWebhook($this->webhook);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);
        $slack = $notification->toSlack($this->user);

        $this->assertContains('Successful Login Attempt', $mail->subject);
        $this->assertEmpty($db);
        $this->assertContains('Your account has just been accessed from a new device.', $slack->content);
    }

    /**
     * Test the failed login notification.
     *
     * @return void
     */
    public function testLoginFailedNotification()
    {
        $notification = new LoginFailed();
        $notification->setSlackWebhook($this->webhook);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);
        $slack = $notification->toSlack($this->user);

        $this->assertContains('Failed Login Attempt', $mail->subject);
        $this->assertEmpty($db);
        $this->assertContains('An unsuccessful login attempt has just been made.', $slack->content);
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
        factory(User::class)->create()->assignedTickets()->save($ticket);

        $notification = new TicketAssigned($ticket->fresh());
        $notification->setSlackWebhook($this->webhook);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);
        $slack = $notification->toSlack($this->user);

        $this->assertContains('Ticket Assigned', $mail->subject);
        $this->assertArraySubset(['ticket_id', 'agent_id'], array_keys($db));
        $this->assertContains('The following ticket has been assigned to', $slack->content);
    }

    /**
     * Test ticket closed notification.
     *
     * @return void
     */
    public function testAgentTicketClosedNotification()
    {
        $department = Department::first();

        /** @var Ticket $ticket */
        $ticket = factory(Ticket::class)->create(['department_id' => $department->id]);
        $ticket->posts()->save(factory(TicketPost::class)->make());

        $notification = new \App\Notifications\Agent\TicketClosed($ticket);
        $notification->setSlackWebhook($this->webhook);

        /** @var User $agent */
        $agent = factory(User::class)->create();
        $agent->roles()->save(Role::agent());
        $agent->departments()->save($department);

        $mail = $notification->toMail($agent);
        $db = $notification->toArray($agent);
        $slack = $notification->toSlack($agent);

        $this->assertContains('Ticket Closed', $mail->subject);
        $this->assertArraySubset(['ticket_id'], array_keys($db));
        $this->assertContains('The following ticket has been closed.', $slack->content);
    }

    /**
     * Test ticket closed notification.
     *
     * @return void
     */
    public function testUserTicketClosedNotification()
    {
        $department = Department::first();

        /** @var Ticket $ticket */
        $ticket = factory(Ticket::class)->create(['department_id' => $department->id]);
        $ticket->posts()->save(factory(TicketPost::class)->make());

        $notification = new \App\Notifications\User\TicketClosed($ticket);
        $notification->setSlackWebhook($this->webhook);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);
        $slack = $notification->toSlack($this->user);

        $this->assertContains('Ticket Closed', $mail->subject);
        $this->assertArraySubset(['ticket_id'], array_keys($db));
        $this->assertContains('The following ticket has been closed.', $slack->content);
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

        $notification = new TicketSubmitted($ticket);
        $notification->setSlackWebhook($this->webhook);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);
        $slack = $notification->toSlack($this->user);

        $this->assertContains('New Ticket Submitted', $mail->subject);
        $this->assertArraySubset(['ticket_id'], array_keys($db));
        $this->assertRegExp('/A new ticket has been submitted to the .* department./', $slack->content);
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

        $notification = new TicketTransferred($ticket);
        $notification->setSlackWebhook($this->webhook);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);
        $slack = $notification->toSlack($this->user);

        $this->assertContains('Ticket Transferred', $mail->subject);
        $this->assertArraySubset(['ticket_id', 'old_department', 'new_department'], array_keys($db));
        $this->assertRegExp('/A new ticket has been transferred to the .* department./', $slack->content);
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

        $notification = new \App\Notifications\Agent\NewTicketPost($ticket);
        $notification->setSlackWebhook($this->webhook);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);
        $slack = $notification->toSlack($this->user);

        $this->assertContains('New Reply', $mail->subject);
        $this->assertArraySubset(['ticket_id'], array_keys($db));
        $this->assertContains('The following ticket has received a new response.', $slack->content);
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

        $notification = new \App\Notifications\User\NewTicketPost($ticket);
        $notification->setSlackWebhook($this->webhook);
        $mail = $notification->toMail($this->user);
        $db = $notification->toArray($this->user);
        $slack = $notification->toSlack($this->user);

        $this->assertContains('New Reply', $mail->subject);
        $this->assertArraySubset(['ticket_id'], array_keys($db));
        $this->assertContains('The following ticket has received a new response.', $slack->content);
    }
}
