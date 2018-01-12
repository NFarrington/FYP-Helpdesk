<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersFacebookChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->default(null)->change();

            $table->string('facebook_id')->nullable()->unique()->after('remember_token');
            $table->json('facebook_data')->nullable()->after('facebook_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();

            $table->dropColumn('facebook_id');
            $table->dropColumn('facebook_data');
        });
    }
}
