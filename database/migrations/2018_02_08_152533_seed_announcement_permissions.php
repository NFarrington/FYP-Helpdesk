<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedAnnouncementPermissions extends Migration
{
    private $permissions = [
        ['key' => 'announcements.view', 'name' => 'Announcements | View', 'description' => 'Allows users to view all announcements.', 'default' => 0],
        ['key' => 'announcements.create', 'name' => 'Announcements | Create', 'description' => 'Allows users to create announcements.', 'default' => 0],
        ['key' => 'announcements.update', 'name' => 'Announcements | Update', 'description' => 'Allows users to update all announcements.', 'default' => 0],
        ['key' => 'announcements.delete', 'name' => 'Announcements | Delete', 'description' => 'Allows users to delete all announcements.', 'default' => 0],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert($this->permissions);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $permissionKeys = collect($this->permissions)->pluck('key');

        DB::table('permissions')->whereIn('key', $permissionKeys)->delete();
    }
}
