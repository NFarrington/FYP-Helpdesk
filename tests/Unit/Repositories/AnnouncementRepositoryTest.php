<?php

namespace Tests\Unit\Repositories;

use App\Models\Announcement;
use App\Repositories\AnnouncementRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The repository.
     *
     * @var AnnouncementRepository
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

        $this->repository = $this->app->make(AnnouncementRepository::class);
    }

    /**
     * Test the getPublished() method.
     *
     * @covers \App\Repositories\AnnouncementRepository::getPublished()
     */
    public function testGetPublished()
    {
        factory(Announcement::class)->states('published')->create();

        $publishedAnnouncements = $this->repository->getPublished();

        $this->assertEquals(1, $publishedAnnouncements->count());
    }
}
