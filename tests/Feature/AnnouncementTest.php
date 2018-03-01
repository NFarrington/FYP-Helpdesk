<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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

        $this->actingAs($this->privilegedUser);
    }

    /**
     * Test an announcement can be created.
     *
     * @return void
     */
    public function testAnnouncementCanBeCreated()
    {
        $announcement = factory(Announcement::class)->make();
        $nextID = DB::select("SHOW TABLE STATUS LIKE 'announcements'")[0]->Auto_increment;

        $this->get(route('announcements.create'));
        $response = $this->post(route('announcements.store'), [
            'title' => $announcement->title,
            'content' => $announcement->content,
            'status' => 0,
        ]);

        $response->assertRedirect(route('announcements.show', $nextID));
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

        $response = $this->get(route('announcements.show', $announcement));

        $response->assertStatus(200);
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

        $this->get(route('announcements.edit', $existingAnnouncement));
        $response = $this->put(route('announcements.update', $existingAnnouncement), [
            'content' => $announcement->content,
        ] + $existingAnnouncement->toArray());

        $response->assertRedirect(route('announcements.show', $existingAnnouncement));
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

        $this->get(route('announcements.edit', $announcement));
        $response = $this->delete(route('announcements.destroy', $announcement));

        $response->assertRedirect(route('announcements.index'));
        $this->assertDatabaseMissing($announcement->getTable(), [
            'id' => $announcement->id,
        ]);
    }

    /**
     * Test announcements are listed properly.
     *
     * @return void
     */
    public function testAnnouncementsCanBeListed()
    {
        $this->actingAs($this->user);

        $publishedAnnouncement = factory(Announcement::class)->states('published')->create(['user_id' => $this->user->id]);
        $unpublishedAnnouncement = factory(Announcement::class)->states('unpublished')->create(['user_id' => $this->user->id]);

        $response = $this->get(route('announcements.index'));
        $response->assertSeeText($publishedAnnouncement->title);
        $response->assertDontSeeText($unpublishedAnnouncement->title);

        $this->actingAs($this->privilegedUser);

        $response = $this->get(route('announcements.index'));
        $response->assertSeeText($publishedAnnouncement->title);
        $response->assertSeeText($unpublishedAnnouncement->title);
    }

    /**
     * Test an announcement can be published.
     *
     * @return void
     */
    public function testAnnouncementCanBePublished()
    {
        $this->actingAs($this->user);

        $announcement = factory(Announcement::class)->states('published')->create(['user_id' => $this->user->id]);

        $response = $this->get(route('announcements.index'));
        $response->assertSeeText($announcement->title);
    }

    /**
     * Test an announcement can be unpublished.
     *
     * @return void
     */
    public function testAnnouncementCanBeUnpublished()
    {
        $this->actingAs($this->user);

        $announcement = factory(Announcement::class)->states('unpublished')->create(['user_id' => $this->user->id]);

        $response = $this->get(route('announcements.index'));
        $response->assertDontSeeText($announcement->title);
    }
}
