<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedRolesTable extends Migration
{
    protected $roles = [
        ['name' => 'Administrator'],
        ['name' => 'Agent'],
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
