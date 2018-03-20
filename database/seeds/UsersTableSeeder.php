<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class, 20)->create();

        $users = factory(\App\Models\User::class, 10)->create();
        foreach ($users as $user) {
            $user->roles()->attach(\App\Models\Role::agent());
            if ($user->id % 2 == 0) {
                $user->roles()->attach(\App\Models\Role::query()->inRandomOrder()->limit(2)->get());
            }
        }

        $users = factory(\App\Models\User::class, 5)->create();
        foreach ($users as $user) {
            $user->roles()->attach(\App\Models\Role::admin());
            if ($user->id % 2 == 0) {
                $user->roles()->attach(\App\Models\Role::query()->inRandomOrder()->limit(2)->get());
            }
        }
    }
}
