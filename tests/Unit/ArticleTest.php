<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\Ticket;
use App\Models\TicketDepartment;
use App\Models\TicketPost;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->user = factory(User::class)->create();
    }

    /**
     * Test an article can be identified as published.
     *
     * @return void
     */
    public function testArticleCanBePublished()
    {
        $article = factory(Article::class)->states('published')->create();

        $this->assertTrue($article->isPublished());
    }

    /**
     * Test an article can be identified as unpublished.
     *
     * @return void
     */
    public function testArticleCanBeUnpublished()
    {
        $article = factory(Article::class)->states('unpublished')->create();

        $this->assertFalse($article->isPublished());
    }
}
