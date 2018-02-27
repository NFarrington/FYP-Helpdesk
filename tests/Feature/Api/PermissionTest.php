<?php

namespace Tests\Feature\Api;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        Passport::actingAs($this->user);

        $this->permission = factory(Permission::class)->create();
    }

    /**
     * Test the permission index loads successfully.
     *
     * @return void
     */
    public function testPermissionIndexPageLoads()
    {
        $response = $this->get(route('api.permissions.index'));

        $response->assertStatus(200);
        $response->assertSeeText($this->permission->name);
    }

    /**
     * Test a permission can be viewed.
     *
     * @return void
     */
    public function testPermissionCanBeViewed()
    {
        $permission = factory(Permission::class)->create();

        $response = $this->get(route('api.permissions.show', $permission->id));

        $response->assertStatus(200);
        $response->assertSeeText($permission->name);
    }

    /**
     * Test the permission can be updated successfully.
     *
     * @return void
     */
    public function testPermissionCanBeEdited()
    {
        $permission = factory(Permission::class)->make();

        $response = $this->put(route('api.permissions.update', $this->permission), [
            'name' => $permission->name,
            'description' => $permission->description,
            'permissions' => [],
        ]);

        $response->assertSuccessful();
        $this->assertArraySubset([
            'name' => $permission->name,
            'description' => $permission->description,
        ], $this->permission->fresh()->toArray());
    }
}
