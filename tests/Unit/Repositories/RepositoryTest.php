<?php

namespace Tests\Unit\Repositories;

use App\Models\Announcement;
use App\Repositories\AnnouncementRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepositoryTest extends TestCase
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
     * Test the getAll() method.
     *
     * @covers \App\Repositories\Repository::getAll()
     */
    public function testGetAll()
    {
        factory(Announcement::class)->times(3)->create();

        $announcements = $this->repository->getAll();

        $this->assertEquals(3, $announcements->count());
    }

    /**
     * Test the getById() method.
     *
     * @covers \App\Repositories\Repository::getById()
     */
    public function testGetById()
    {
        $original = factory(Announcement::class)->create();

        $fresh = $this->repository->getById($original->id);

        $this->assertEquals($original->id, $fresh->id);
    }
}
