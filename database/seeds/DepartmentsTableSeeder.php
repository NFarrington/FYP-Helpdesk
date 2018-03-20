<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Department::class, 10)->states('internal')->create();
        factory(\App\Models\Department::class, 10)->states('external')->create();
    }
}
