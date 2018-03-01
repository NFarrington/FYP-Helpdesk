<?php

namespace Tests\Feature\Api;

use App\Models\Article;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test user.
     *
     * @var User
     */
    protected $user;

    /**
     * A privileged test user.
     *
     * @var User
     */
    protected $privilegedUser;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $role = Role::where('name', 'Administrator')->first();
        $this->privilegedUser = factory(User::class)->create();
        $this->privilegedUser->roles()->attach($role);

        Passport::actingAs($this->privilegedUser);
    }

    /**
     * Test an article can be created.
     *
     * @return void
     */
    public function testArticleCanBeCreated()
    {
        $article = factory(Article::class)->make();

        $response = $this->post(route('api.articles.store'), [
            'title' => $article->title,
            'content' => $article->content,
            'visible_from_date' => Carbon::now()->toDateString(),
            'visible_from_time' => Carbon::now()->format('H:i'),
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas($article->getTable(), [
            'title' => $article->title,
            'content' => $article->content,
        ]);
    }

    /**
     * Test an article can be viewed.
     *
     * @return void
     */
    public function testArticleCanBeViewed()
    {
        $article = factory(Article::class)->create();

        $response = $this->get(route('api.articles.show', $article));

        $response->assertStatus(200);
        $response->assertSee($article->title);
    }

    /**
     * Test an article can be updated.
     *
     * @return void
     */
    public function testArticleCanBeUpdated()
    {
        $existingArticle = factory(Article::class)->create();
        $article = factory(Article::class)->make();

        $response = $this->put(route('api.articles.update', $existingArticle), [
                'content' => $article->content,
            ] + $existingArticle->toArray());

        $response->assertSuccessful();
        $this->assertDatabaseHas($article->getTable(), [
            'id' => $existingArticle->id,
            'content' => $article->content,
        ]);
    }

    /**
     * Test an article can be deleted.
     *
     * @return void
     */
    public function testArticleCanBeDeleted()
    {
        $article = factory(Article::class)->create();

        $response = $this->delete(route('api.articles.destroy', $article));

        $response->assertSuccessful();
        $this->assertDatabaseMissing($article->getTable(), [
            'id' => $article->id,
        ]);
    }

    /**
     * Test articles are listed properly.
     *
     * @return void
     */
    public function testArticlesCanBeListed()
    {
        $publishedArticle = factory(Article::class)->states('published')->create();
        $unpublishedArticle = factory(Article::class)->states('unpublished')->create();

        Passport::actingAs($this->user);

        $response = $this->get(route('api.articles.index'));
        $response->assertSeeText($publishedArticle->title);
        $response->assertDontSeeText($unpublishedArticle->title);

        Passport::actingAs($this->privilegedUser);

        $response = $this->get(route('api.articles.index'));
        $response->assertSeeText($publishedArticle->title);
        $response->assertSeeText($unpublishedArticle->title);
    }
}
