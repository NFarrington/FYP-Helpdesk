<?php

namespace Tests\Unit\Services;

use App\Models\Article;
use App\Models\User;
use App\Services\ArticleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The service.
     *
     * @var ArticleService
     */
    protected $service;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->service = $this->app->make(ArticleService::class);
    }

    /**
     * Test the create method.
     *
     * @covers \App\Services\ArticleService::create()
     */
    public function testCreate()
    {
        $template = factory(Article::class)->make();
        $this->service->create($template->attributesToArray(), $template->user);
        $this->assertDatabaseHas($template->getTable(), $template->attributesToArray());
    }

    /**
     * Test the update method.
     *
     * @covers \App\Services\ArticleService::update()
     */
    public function testUpdate()
    {
        $article = factory(Article::class)->create();

        $template = factory(Article::class)->make();
        $this->service->update($article, $template->attributesToArray());
        $this->assertDatabaseHas($template->getTable(), $template->attributesToArray());
    }

    /**
     * Test the delete method.
     *
     * @covers \App\Services\ArticleService::delete()
     * @throws \Exception
     */
    public function testDelete()
    {
        $article = factory(Article::class)->create();

        $this->service->delete($article);
        $this->assertDatabaseMissing($article->getTable(), $article->attributesToArray());
    }

    /**
     * Test the getViewableBy method.
     *
     * @covers \App\Services\ArticleService::getViewableBy()
     */
    public function testGetViewableBy()
    {
        factory(Article::class)->states('published')->create();
        factory(Article::class)->states('unpublished')->create();

        $user = mock(User::class);
        $user->allows()->can('view', Article::class)->andReturns(true);
        $viewable = $this->service->getViewableBy($user);
        $this->assertEquals(2, $viewable->count());

        $user = mock(User::class);
        $user->allows()->can('view', Article::class)->andReturns(false);
        $viewable = $this->service->getViewableBy($user);
        $this->assertEquals(1, $viewable->count());
    }
}
