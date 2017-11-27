<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedPermissionRoleTable extends Migration
{
    /**
     * Retrieves a list of permissions to insert for the administrator role.
     *
     * @return array
     */
    private function administratorRolePermissions()
    {
        $roleID = DB::table('roles')->where('name', 'Administrator')->first()->id;
        $permissionIDs = DB::table('permissions')->pluck('id');

        $rolePermissions = [];
        foreach ($permissionIDs as $permissionID) {
            $rolePermissions[] = ['role_id' => $roleID, 'permission_id' => $permissionID];
        }

        return $rolePermissions;
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permission_role')->insert($this->administratorRolePermissions());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->administratorRolePermissions() as $rolePermission) {
            DB::table('permission_role')->where($rolePermission)->delete();
        }
    }
}
