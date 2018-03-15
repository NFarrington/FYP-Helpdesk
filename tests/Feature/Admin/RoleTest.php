<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test user.
     *
     * @var User
     */
    protected $user;

    /**
     * A basic test role.
     *
     * @var Role
     */
    protected $role;

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

        $this->role = factory(Role::class)->create();
    }

    /**
     * Test the role index loads successfully.
     *
     * @return void
     */
    public function testRoleIndexPageLoads()
    {
        $response = $this->get(route('admin.roles.index'));

        $response->assertStatus(200);
        $response->assertSeeText($this->role->name);
    }

    public function testRoleCreatePageLoads()
    {
        $response = $this->get(route('admin.roles.create'));

        $response->assertStatus(200);
    }

    public function testRoleCanBeCreated()
    {
        $role = factory(Role::class)->make();
        $this->get(route('admin.roles.create'));
        $response = $this->post(route('admin.roles.store'), [
            'key' => $role->key,
            'name' => $role->name,
            'description' => $role->description,
            'permissions' => [],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', trans('role.created'));
        $this->assertDatabaseHas('roles', [
            'key' => $role->key,
            'name' => $role->name,
            'description' => $role->description,
        ]);
    }

    public function testRoleShowPageRedirects()
    {
        $response = $this->get(route('admin.roles.show', $this->role));

        $response->assertRedirect(route('admin.roles.edit', $this->role));
    }

    /**
     * Test the role edit page loads successfully.
     *
     * @return void
     */
    public function testRoleEditPageLoads()
    {
        $response = $this->get(route('admin.roles.edit', $this->role));

        $response->assertStatus(200);
        $response->assertSee($this->role->name);
    }

    /**
     * Test the role can be updated successfully.
     *
     * @return void
     */
    public function testRoleCanBeEdited()
    {
        $role = factory(Role::class)->make();

        $this->get(route('admin.roles.edit', $this->role));
        $response = $this->put(route('admin.roles.update', $this->role), [
            'name' => $role->name,
            'description' => $role->description,
            'permissions' => [],
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $response->assertSessionHas('status', trans('role.updated'));
        $this->assertArraySubset([
            'name' => $role->name,
            'description' => $role->description,
        ], $this->role->fresh()->toArray());
    }
}
