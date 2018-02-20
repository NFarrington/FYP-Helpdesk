<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedPermissionsTable extends Migration
{
    private $permissions = [
        ['key' => 'articles.view', 'name' => 'Articles | View', 'description' => 'Allows users to view knowledgebase articles.', 'default' => 0],
        ['key' => 'articles.create', 'name' => 'Articles | Create', 'description' => 'Allows users to create knowledgebase articles.', 'default' => 0],
        ['key' => 'articles.update', 'name' => 'Articles | Update', 'description' => 'Allows users to update knowledgebase articles.', 'default' => 0],
        ['key' => 'articles.delete', 'name' => 'Articles | Delete', 'description' => 'Allows users to delete knowledgebase articles.', 'default' => 0],
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
