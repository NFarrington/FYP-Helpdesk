<?php

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DepartmentsTableSeeder::class,
            RolesTableSeeder::class,
            UsersTableSeeder::class,

            AnnouncementsTableSeeder::class,
            ArticlesTableSeeder::class,
            TicketsTableSeeder::class,
        ]);
    }
}
