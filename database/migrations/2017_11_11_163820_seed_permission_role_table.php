<?php

use Illuminate\Database\Migrations\Migration;

class SeedPermissionRoleTable extends Migration
{
    /**
     * Retrieves a list of permissions to insert for the administrator role.
     *
     * @return array
     */
    private function getRolePermissions()
    {
        $adminId = DB::table('roles')->where('key', 'admin')->first()->id;
        $agentId = DB::table('roles')->where('key', 'agent')->first()->id;
        $permissionIds = DB::table('permissions')->pluck('id');

        $rolePermissions = [];
        foreach ($permissionIds as $permissionId) {
            $rolePermissions[] = ['role_id' => $adminId, 'permission_id' => $permissionId];
            $rolePermissions[] = ['role_id' => $agentId, 'permission_id' => $permissionId];
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
        DB::table('permission_role')->insert($this->getRolePermissions());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->getRolePermissions() as $rolePermission) {
            DB::table('permission_role')->where($rolePermission)->delete();
        }
    }
}
