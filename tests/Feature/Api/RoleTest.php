<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
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
        Passport::actingAs($this->user);

        $this->role = factory(Role::class)->create();
    }

    /**
     * Test the role index loads successfully.
     *
     * @return void
     */
    public function testRoleIndexPageLoads()
    {
        $response = $this->get(route('api.roles.index'));

        $response->assertStatus(200);
        $response->assertSeeText($this->role->name);
    }

    /**
     * Test a role can be viewed.
     *
     * @return void
     */
    public function testRoleCanBeViewed()
    {
        $role = factory(Role::class)->create();

        $response = $this->get(route('api.roles.show', $role->id));

        $response->assertStatus(200);
        $response->assertSeeText($role->name);
    }

    /**
     * Test the role can be updated successfully.
     *
     * @return void
     */
    public function testRoleCanBeEdited()
    {
        $role = factory(Role::class)->make();

        $response = $this->put(route('api.roles.update', $this->role), [
            'name' => $role->name,
            'description' => $role->description,
            'permissions' => [],
        ]);

        $response->assertSuccessful();
        $this->assertArraySubset([
            'name' => $role->name,
            'description' => $role->description,
        ], $this->role->fresh()->toArray());
    }
}
