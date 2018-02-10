<?php

namespace Tests\Unit;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementTest extends TestCase
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
     * Test an announcement can be identified as active.
     *
     * @return void
     */
    public function testAnnouncementCanBeActive()
    {
        $announcement = factory(Announcement::class)->states('active')->create(['user_id' => $this->user]);

        $this->assertTrue($announcement->isActive());
    }

    /**
     * Test an announcement can be identified as published.
     *
     * @return void
     */
    public function testAnnouncementCanBePublished()
    {
        $announcement = factory(Announcement::class)->states('published')->create(['user_id' => $this->user]);

        $this->assertTrue($announcement->isPublished());
    }

    /**
     * Test an announcement can be identified as unpublished.
     *
     * @return void
     */
    public function testAnnouncementCanBeUnpublished()
    {
        $announcement = factory(Announcement::class)->states('unpublished')->create(['user_id' => $this->user]);

        $this->assertFalse($announcement->isPublished());
    }
}
