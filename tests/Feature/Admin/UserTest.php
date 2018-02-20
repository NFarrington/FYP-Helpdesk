<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
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
        $this->user->roles()->attach(Role::admin()->id);
        $this->actingAs($this->user);
    }

    /**
     * Test the user index loads successfully.
     *
     * @return void
     */
    public function testUserIndexPageLoads()
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSeeText(e($this->user->name));
    }

    /**
     * Test the user edit page loads successfully.
     *
     * @return void
     */
    public function testUserEditPageLoads()
    {
        $response = $this->get(route('admin.users.edit', $this->user));

        $response->assertStatus(200);
        $response->assertSee(e($this->user->name));
    }

    /**
     * Test the user can be updated successfully.
     *
     * @return void
     */
    public function testUserCanBeEdited()
    {
        $user = factory(User::class)->make();

        $this->get(route('admin.users.edit', $this->user));
        $response = $this->put(route('admin.users.update', $this->user), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => str_random(40),
            'roles' => [],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('status', trans('user.updated'));
        $this->assertArraySubset([
            'name' => $user->name,
            'email' => $user->email,
            'email_verified' => true,
        ], $this->user->fresh()->toArray());
    }
}
