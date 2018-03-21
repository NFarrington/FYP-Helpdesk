<?php

use Illuminate\Database\Migrations\Migration;

class SeedPermissionsTable extends Migration
{
    private $permissions = [
        ['key' => 'announcements.view', 'name' => 'Announcements | View', 'description' => 'Allows users to view any announcement.'],
        ['key' => 'announcements.create', 'name' => 'Announcements | Create', 'description' => 'Allows users to create announcements.'],
        ['key' => 'announcements.update', 'name' => 'Announcements | Update', 'description' => 'Allows users to update any announcement.'],
        ['key' => 'announcements.delete', 'name' => 'Announcements | Delete', 'description' => 'Allows users to delete any announcement.'],
        ['key' => 'articles.view', 'name' => 'Articles | View', 'description' => 'Allows users to view any knowledgebase article.'],
        ['key' => 'articles.create', 'name' => 'Articles | Create', 'description' => 'Allows users to create knowledgebase articles.'],
        ['key' => 'articles.update', 'name' => 'Articles | Update', 'description' => 'Allows users to update any knowledgebase article.'],
        ['key' => 'articles.delete', 'name' => 'Articles | Delete', 'description' => 'Allows users to delete any knowledgebase article.'],
        ['key' => 'tickets.posts.update', 'name' => 'Ticket Posts | Update', 'description' => 'Allows users to modify any ticket post.'],
        ['key' => 'tickets.posts.delete', 'name' => 'Ticket Posts | Delete', 'description' => 'Allows users to delete any ticket post.'],
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
