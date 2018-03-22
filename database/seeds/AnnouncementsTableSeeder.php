<?php

class AnnouncementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\Models\User::whereHas('roles')->inRandomOrder()->limit(20)->get();
        foreach ($users as $user) {
            factory(\App\Models\Announcement::class, 2)
                ->create(['user_id' => $user->id]);
        }

        foreach ($users->take(5) as $user) {
            factory(\App\Models\Announcement::class, 2)
                ->states('published')
                ->create(['user_id' => $user->id]);
        }
    }
}
