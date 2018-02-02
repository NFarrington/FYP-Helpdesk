<?php

use Illuminate\Database\Migrations\Migration;

class SeedTicketPostPermissions extends Migration
{
    private $permissions = [
        ['key' => 'tickets.posts.update', 'name' => 'Ticket Posts | Update', 'description' => 'Allows users to update ticket posts.', 'default' => 0],
        ['key' => 'tickets.posts.delete', 'name' => 'Ticket Posts | Delete', 'description' => 'Allows users to delete ticket posts.', 'default' => 0],
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
