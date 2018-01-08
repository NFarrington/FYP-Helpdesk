<?php

namespace Tests\Feature\Admin;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test user.
     *
     * @var User
     */
    protected $user;

    /**
     * A basic test permission.
     *
     * @var Permission
     */
    protected $permission;

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

        $this->permission = factory(Permission::class)->create();
    }

    /**
     * Test the permission index loads successfully.
     *
     * @return void
     */
    public function testPermissionIndexPageLoads()
    {
        $response = $this->get(route('admin.permissions.index'));

        $response->assertStatus(200);
        $response->assertSeeText($this->permission->name);
    }

    /**
     * Test the permission edit page loads successfully.
     *
     * @return void
     */
    public function testPermissionEditPageLoads()
    {
        $response = $this->get(route('admin.permissions.edit', $this->permission));

        $response->assertStatus(200);
        $response->assertSee($this->permission->name);
    }

    /**
     * Test the permission can be updated successfully.
     *
     * @return void
     */
    public function testPermissionCanBeEdited()
    {
        $permission = factory(Permission::class)->make();

        $this->get(route('admin.permissions.edit', $this->permission));
        $response = $this->put(route('admin.permissions.update', $this->permission), [
            'name' => $permission->name,
            'description' => $permission->description,
            'permissions' => [],
        ]);

        $response->assertRedirect(route('admin.permissions.index'));
        $response->assertSessionHas('status', trans('permission.updated'));
        $this->assertArraySubset([
            'name' => $permission->name,
            'description' => $permission->description,
        ], $this->permission->fresh()->toArray());
    }
}
