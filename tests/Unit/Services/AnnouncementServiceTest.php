<?php

namespace Tests\Unit\Services;

use App\Models\Announcement;
use App\Models\User;
use App\Repositories\AnnouncementRepository;
use App\Services\AnnouncementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class AnnouncementServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The announcement service.
     *
     * @var AnnouncementService
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

        $this->service = $this->app->make(AnnouncementService::class);
    }

    /**
     * Test the create method.
     *
     * @covers \App\Services\AnnouncementService::create()
     */
    public function testCreate()
    {
        $template = factory(Announcement::class)->make();
        $this->service->create($template->toArray(), $template->user);
        $this->assertDatabaseHas($template->getTable(), $template->attributesToArray());
    }


    /**
     * Test the update method.
     *
     * @covers \App\Services\AnnouncementService::update()
     */
    public function testUpdate()
    {
        $announcement = factory(Announcement::class)->create();

        $template = factory(Announcement::class)->make(['user_id' => $announcement->user->id]);
        $this->service->update($announcement, $template->toArray());
        $this->assertDatabaseHas($template->getTable(), $template->toArray());

        $template = factory(Announcement::class)->make();
        $this->service->update($announcement, $template->toArray(), $template->user);
        $this->assertDatabaseHas($template->getTable(), $template->attributesToArray());
    }

    /**
     * Test the delete method.
     *
     * @covers \App\Services\AnnouncementService::delete()
     * @throws \Exception
     */
    public function testDelete()
    {
        $announcement = factory(Announcement::class)->create();

        $this->service->delete($announcement);
        $this->assertDatabaseMissing($announcement->getTable(), $announcement->attributesToArray());

    }

    /**
     * Test the getViewableBy method.
     *
     * @covers \App\Services\AnnouncementService::getViewableBy()
     */
    public function testGetViewableBy()
    {
        factory(Announcement::class)->states('published')->create();
        factory(Announcement::class)->states('unpublished')->create();

        $user = mock(User::class);
        $user->allows()->can('view', Announcement::class)->andReturns(true);
        $viewable = $this->service->getViewableBy($user);
        $this->assertEquals(2, $viewable->count());

        $user = mock(User::class);
        $user->allows()->can('view', Announcement::class)->andReturns(false);
        $viewable = $this->service->getViewableBy($user);
        $this->assertEquals(1, $viewable->count());
    }
}
