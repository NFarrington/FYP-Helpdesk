<?php

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Article::class, 20)->states('unpublished')->create();
        factory(\App\Models\Article::class, 20)->states('published')->create();
    }
}
