<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\TicketPost::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'ticket_id' => function () {
            return factory(App\Models\Ticket::class)->create()->id;
        },
        'content' => $faker->paragraph(),
    ];
});
