<?php

namespace Tests\Unit;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a permission has roles associated with it.
     *
     * @return void
     */
    public function testPermissionHasRoles()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();
        DB::table('permission_role')->insert([
            'permission_id' => $permission->id,
            'role_id' => $role->id,
        ]);

        $this->assertEquals($role->id, $permission->roles()->first()->id);
    }
}
