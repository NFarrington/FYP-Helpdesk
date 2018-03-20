<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Article::class, 10)->states('unpublished')->create();
        factory(\App\Models\Article::class, 10)->states('published')->create();
    }
}
