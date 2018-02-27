<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketPost;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketPostTest extends TestCase
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
        Passport::actingAs($this->user);
    }

    /**
     * Test a submitted ticket can be replied to.
     *
     * @return void
     */
    public function testTicketPostCanBeCreated()
    {
        $ticket = factory(Ticket::class)->create(['user_id' => $this->user->id]);
        $ticketPost = factory(TicketPost::class)->make();

        $response = $this->post(route('api.tickets.posts.store', $ticket->id), [
            'reply' => $ticketPost->content,
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas($ticketPost->getTable(), ['content' => $ticketPost->content]);
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

        $content = str_random();
        $response = $this->put(route('api.tickets.posts.update', [$ticket, $post]), [
            'content' => $content,
        ]);

        $response->assertSuccessful();

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

        $response = $this->delete(route('api.tickets.posts.update', [$ticket, $post]));

        $response->assertSuccessful();
        $this->assertDatabaseMissing($post->getTable(), ['id' => $post->id]);
    }
}
