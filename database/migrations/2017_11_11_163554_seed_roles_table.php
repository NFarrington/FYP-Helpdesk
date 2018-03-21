<?php

use Illuminate\Database\Migrations\Migration;

class SeedRolesTable extends Migration
{
    protected $roles = [
        ['key' => 'admin', 'name' => 'Administrator', 'description' => 'Provides elevated permissions to configure and manage the application'],
        ['key' => 'agent', 'name' => 'Agent', 'description' => 'Provides access to agent-related customer-service infrastructure'],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('roles')->insert($this->roles);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $roles = collect($this->roles)->pluck('name');

        DB::table('roles')->whereIn('name', $roles)->delete();
    }
}
