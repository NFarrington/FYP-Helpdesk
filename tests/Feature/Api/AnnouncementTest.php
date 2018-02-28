<?php

namespace Tests\Feature\Api;

use App\Models\Announcement;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
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

        $role = Role::admin();
        $role->permissions()->sync(Permission::pluck('id'));
        $this->privilegedUser = factory(User::class)->create();
        $this->privilegedUser->roles()->attach($role);

        Passport::actingAs($this->privilegedUser);
    }

    /**
     * Test announcements can be indexed.
     *
     * @return void
     */
    public function testAnnouncementsCanBeIndexed()
    {
        $publishedAnnouncement = factory(Announcement::class)->states('published')->create(['user_id' => $this->user->id]);
        $unpublishedAnnouncement = factory(Announcement::class)->states('unpublished')->create(['user_id' => $this->user->id]);

        Passport::actingAs($this->user);

        $response = $this->get(route('api.announcements.index'));
        $response->assertSeeText($publishedAnnouncement->title);
        $response->assertDontSeeText($unpublishedAnnouncement->title);

        Passport::actingAs($this->privilegedUser);

        $response = $this->get(route('api.announcements.index'));
        $response->assertSeeText($publishedAnnouncement->title);
        $response->assertSeeText($unpublishedAnnouncement->title);
    }

    /**
     * Test an announcement can be created.
     *
     * @return void
     */
    public function testAnnouncementCanBeCreated()
    {
        $announcement = factory(Announcement::class)->make();

        $response = $this->post(route('api.announcements.store'), [
            'title' => $announcement->title,
            'content' => $announcement->content,
            'status' => 0,
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas($announcement->getTable(), [
            'title' => $announcement->title,
            'content' => $announcement->content,
        ]);
    }

    /**
     * Test an announcement can be viewed.
     *
     * @return void
     */
    public function testAnnouncementCanBeViewed()
    {
        $announcement = factory(Announcement::class)->create(['user_id' => $this->user->id]);

        $response = $this->get(route('api.announcements.show', $announcement));

        $response->assertSuccessful();
        $response->assertSee($announcement->title);
    }

    /**
     * Test an announcement can be updated.
     *
     * @return void
     */
    public function testAnnouncementCanBeUpdated()
    {
        $existingAnnouncement = factory(Announcement::class)->create(['user_id' => $this->user->id]);
        $announcement = factory(Announcement::class)->make();

        $response = $this->put(route('api.announcements.update', $existingAnnouncement), [
                'content' => $announcement->content,
            ] + $existingAnnouncement->toArray());

        $response->assertSuccessful();
        $this->assertDatabaseHas($announcement->getTable(), [
            'id' => $existingAnnouncement->id,
            'content' => $announcement->content,
        ]);
    }

    /**
     * Test an announcement can be deleted.
     *
     * @return void
     */
    public function testAnnouncementCanBeDeleted()
    {
        $announcement = factory(Announcement::class)->create(['user_id' => $this->user->id]);

        $response = $this->delete(route('api.announcements.destroy', $announcement));

        $response->assertSuccessful();
        $this->assertDatabaseMissing($announcement->getTable(), [
            'id' => $announcement->id,
        ]);
    }
}
