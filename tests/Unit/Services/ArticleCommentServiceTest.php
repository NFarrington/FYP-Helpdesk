<?php

namespace Tests\Unit\Services;

use App\Models\ArticleComment;
use App\Services\ArticleCommentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleCommentServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The service.
     *
     * @var ArticleCommentService
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

        $this->service = $this->app->make(ArticleCommentService::class);
    }

    /**
     * Test the create method.
     *
     * @covers \App\Services\AnnouncementService::create()
     */
    public function testCreate()
    {
        $template = factory(ArticleComment::class)->make();
        $this->service->create($template->toArray(), $template->article, $template->user);
        $this->assertDatabaseHas($template->getTable(), $template->attributesToArray());
    }
}
