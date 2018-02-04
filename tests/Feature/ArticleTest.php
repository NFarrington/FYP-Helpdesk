<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
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

        $this->actingAs($this->privilegedUser);
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
            'visible_from_date' => Carbon::now()->toDateString(),
            'visible_from_time' => Carbon::now()->format('H:i'),
        ]);

        $response->assertRedirect(route('articles.show', $nextID));
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

        $response = $this->get(route('articles.show', $article));

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

        $this->get(route('articles.edit', $existingArticle));
        $response = $this->put(route('articles.update', $existingArticle), [
            'content' => $article->content,
        ] + $existingArticle->toArray());

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
     * Test articles are listed properly.
     *
     * @return void
     */
    public function testArticlesCanBeListed()
    {
        $this->actingAs($this->user);

        $publishedArticle = factory(Article::class)->states('published')->create();
        $unpublishedArticle = factory(Article::class)->states('unpublished')->create();

        $response = $this->get(route('articles.index'));
        $response->assertSeeText($publishedArticle->title);
        $response->assertDontSeeText($unpublishedArticle->title);

        $this->actingAs($this->privilegedUser);

        $response = $this->get(route('articles.index'));
        $response->assertSeeText($publishedArticle->title);
        $response->assertSeeText($unpublishedArticle->title);
    }

    /**
     * Test an article can be published at a specific time.
     *
     * @return void
     */
    public function testArticleCanBePublished()
    {
        $this->actingAs($this->user);

        $article = factory(Article::class)->states('published')->create();

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
        $this->actingAs($this->user);

        $article = factory(Article::class)->states('unpublished')->create();

        $response = $this->get(route('articles.index'));
        $response->assertDontSeeText($article->title);
    }

    /**
     * Test a submitted ticket can be replied to.
     *
     * @return void
     */
    public function testArticleCanBeCommentedOn()
    {
        $article = factory(Article::class)->states('published')->create();
        $articleComment = factory(ArticleComment::class)->make();
        $this->actingAs($this->user);

        $this->get(route('articles.show', $article));
        $response = $this->post(route('articles.comments.store', $article), [
            'content' => $articleComment->content,
        ]);

        $response->assertRedirect(route('articles.show', $article));
        $this->assertDatabaseHas($articleComment->getTable(), ['content' => $articleComment->content]);
    }
}
