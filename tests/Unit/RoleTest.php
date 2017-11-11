<?php

namespace Tests\Unit;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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
     * Test a role is associated with its users.
     *
     * @return void
     */
    public function testRoleHasUsers()
    {
        $role = factory(Role::class)->create();
        DB::table('role_user')->insert([
            'role_id' => $role->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertEquals($this->user->id, $role->users()->first()->id);
    }

    /**
     * Test a role has permissions associated with it.
     *
     * @return void
     */
    public function testRoleHasPermissions()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();
        DB::table('permission_role')->insert([
            'permission_id' => $permission->id,
            'role_id' => $role->id,
        ]);

        $this->assertEquals($permission->id, $role->permissions()->first()->id);
    }
}
