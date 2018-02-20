<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Department;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
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
        $this->user->roles()->sync(Role::all());
        $this->user->departments()->sync(Department::all());

        $this->actingAs($this->user);
    }

    /**
     * Test search returns the expected results.
     *
     * @return void
     */
    public function testSearch()
    {
        $user = factory(User::class)->create();
        $ticket = factory(Ticket::class)->create(['user_id' => $user->id]);
        $article = factory(Article::class)->create();

        $regex = '/[A-Z0-9_]+/i';
        $this->runSearch($regex, $ticket->summary);
        $this->runSearch($regex, $article->title);
        $this->runSearch($regex, $user->name);

        $this->actingAs($user);

        $this->runSearch($regex, $ticket->summary);
        $this->runSearch($regex, e($user->name), false);
    }

    /**
     * Run the search with the given regex and text.
     *
     * @param $regex
     * @param $text
     * @param bool $see
     */
    protected function runSearch($regex, $text, $see = true)
    {
        preg_match($regex, $text, $matches);
        $response = $this->get(route('search', ['q' => $matches[0]]));

        $see
            ? $response->assertSee($matches[0])
            : $response->assertDontSee($matches[0]);
    }

    /**
     * Test search redirects without search parameter.
     *
     * @return void
     */
    public function testSearchValidation()
    {
        $response = $this->get(route('search'));
        $response->assertRedirect(route('home'));
    }
}
