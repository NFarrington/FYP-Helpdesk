<?php

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Department::class, 20)->states('internal')->create();
        factory(\App\Models\Department::class, 20)->states('external')->create();
    }
}
