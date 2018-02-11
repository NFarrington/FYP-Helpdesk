<?php

namespace Tests\Unit\Repositories;

use App\Models\Article;
use App\Repositories\ArticleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The repository.
     *
     * @var ArticleRepository
     */
    protected $repository;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->repository = $this->app->make(ArticleRepository::class);
    }

    /**
     * Test the getPublished() method.
     *
     * @covers \App\Repositories\ArticleRepository::getPublished()
     */
    public function testGetPublished()
    {
        factory(Article::class)->states('published')->create();

        $publishedArticles = $this->repository->getPublished();

        $this->assertEquals(1, $publishedArticles->count());
    }
}
