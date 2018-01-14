<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRolesKeyAndDescriptionColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('key')->after('id');
            $table->string('description')->nullable()->after('name');
        });

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
                'description' => 'Provides access to agent-related customer-service infrastructure'
            ]);

        Schema::table('roles', function (Blueprint $table) {
            $table->string('key')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('key');
            $table->dropColumn('description');
        });
    }
}
