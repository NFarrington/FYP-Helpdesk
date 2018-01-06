<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReseedRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('roles')
            ->where('name', 'Administrator')
            ->update([
                'key' => 'admin',
                'description' => 'Provides elevated permissions to configure and manage the application'
            ]);

        DB::table('roles')
            ->where('name', 'Agent')
            ->update([
                'key' => 'agent',
                'description' => 'Provides access to staff-related customer-service infrastructure'
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // do nothing
    }
}
