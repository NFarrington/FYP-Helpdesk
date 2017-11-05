<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Ticket::class, function (Faker $faker) {
    return [
        'status_id' => function () {
            return DB::table('ticket_statuses')->inRandomOrder()->first()->id;
        },
        'user_id' => factory(App\Models\User::class)->create()->id,
        'summary' => $faker->sentence(),
    ];
});
