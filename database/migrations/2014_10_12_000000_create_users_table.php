<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->boolean('email_verified')->default(0);
            $table->string('password')->nullable()->default(null);
            $table->rememberToken();
            $table->string('google2fa_secret')->nullable();
            $table->string('facebook_id')->nullable()->unique();
            $table->json('facebook_data')->nullable();
            $table->string('google_id')->nullable()->unique();
            $table->json('google_data')->nullable();
            $table->json('notification_settings')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
