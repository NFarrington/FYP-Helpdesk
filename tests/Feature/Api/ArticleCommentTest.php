<?php

namespace Tests\Feature\Api;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ArticleCommentTest extends TestCase
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
     * Test a submitted ticket can be replied to.
     *
     * @return void
     */
    public function testArticleCanBeCommentedOn()
    {
        $article = factory(Article::class)->states('published')->create();
        $articleComment = factory(ArticleComment::class)->make();
        Passport::actingAs($this->user);

        $response = $this->post(route('api.articles.comments.store', $article), [
            'content' => $articleComment->content,
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas($articleComment->getTable(), ['content' => $articleComment->content]);
    }
}
