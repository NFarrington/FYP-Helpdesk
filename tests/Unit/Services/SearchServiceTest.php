<?php

namespace Tests\Unit\Services;

use App\Models\Article;
use App\Models\Department;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use App\Services\SearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The service.
     *
     * @var SearchService
     */
    protected $service;

    /**
     * A test user.
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

        $this->service = $this->app->make(SearchService::class);

        $this->user = factory(User::class)->create();
        $this->user->roles()->sync(Role::all());
        $this->user->departments()->sync(Department::all());
    }

    /**
     * Test the searchArticles method.
     *
     * @covers \App\Services\SearchService::searchArticles()
     */
    public function testSearchArticles()
    {
        $article = factory(Article::class)->create();
        preg_match('/[A-Z0-9_]+/i', $article->title, $matches);

        $result = $this->service->searchArticles($this->user, explode(' ', $matches[0]));
        $this->assertEquals($article->id, $result->first()->id);
    }

    /**
     * Test the searchTickets method.
     *
     * @covers \App\Services\SearchService::searchTickets()
     */
    public function testSearchTickets()
    {
        $user = factory(User::class)->create();
        $ticket = factory(Ticket::class)->create(['user_id' => $user->id]);
        preg_match('/[A-Z0-9_]+/i', $ticket->summary, $matches);

        $result = $this->service->searchTickets($user, explode(' ', $matches[0]));
        $this->assertEquals($ticket->id, $result->first()->id);
    }

    /**
     * Test the searchUsers method.
     *
     * @covers \App\Services\SearchService::searchUsers()
     */
    public function testSearchUsers()
    {
        $user = factory(User::class)->create();
        preg_match('/[A-Z0-9_]+/i', $user->name, $matches);

        $result = $this->service->searchUsers($this->user, explode(' ', $matches[0]));
        $this->assertEquals($user->id, $result->items()[0]->id);
    }
}
