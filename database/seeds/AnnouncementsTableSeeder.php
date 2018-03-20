<?php

use Illuminate\Database\Seeder;

class AnnouncementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\Models\User::whereHas('roles')->inRandomOrder()->limit(5)->get();
        foreach ($users as $user) {
            factory(\App\Models\Announcement::class, 2)
                ->create(['user_id' => $user->id]);
        }
    }
}
