<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $role = Role::where('name', 'Administrator')->first();
        $this->user = factory(User::class)->create();
        $this->user->roles()->attach($role);
        $this->actingAs($this->user);
    }

    /**
     * Test an article can be created.
     *
     * @return void
     */
    public function testArticleCanBeCreated()
    {
        $article = factory(Article::class)->make();
        $nextID = DB::table('articles')->max('id') + 1;

        $this->get(route('articles.create'));
        $response = $this->post(route('articles.store'), [
            'title' => $article->title,
            'content' => $article->content,
        ]);


        $response->assertRedirect(route('articles.show', $nextID));
        $this->assertDatabaseHas($article->getTable(), [
            'title' => $article->title,
            'content' => $article->content,
        ]);
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

        $this->get(route('articles.edit', $existingArticle));
        $response = $this->put(route('articles.update', $existingArticle), [
            'content' => $article->content,
        ]);

        $response->assertRedirect(route('articles.show', $existingArticle));
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

        $this->get(route('articles.edit', $article));
        $response = $this->delete(route('articles.destroy', $article));

        $response->assertRedirect(route('articles.index'));
        $this->assertDatabaseMissing($article->getTable(), [
            'id' => $article->id,
        ]);
    }

    /**
     * Test an article can be published at a specific time.
     *
     * @return void
     */
    public function testArticleCanBePublished()
    {
        $article = factory(Article::class)->states('published')->create();

        $response = $this->get(route('articles.index'));
        $response->assertSeeText($article->title);

        $response = $this->get(route('articles.index'));
        $response->assertSeeText($article->title);
    }

    /**
     * Test an article can be unpublished at a specific time.
     *
     * @return void
     */
    public function testArticleCanBeUnpublished()
    {
        $article = factory(Article::class)->states('unpublished')->create();

        $response = $this->get(route('articles.index'));
        $response->assertDontSeeText($article->title);
    }
}
