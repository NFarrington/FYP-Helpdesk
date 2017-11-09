<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\TicketDepartment::class, function (Faker $faker) {
    return [
        'name' => 'Random - '.str_random(10),
        'description' => $faker->sentence(),
        'internal' => $faker->numberBetween(0, 1),
    ];
});

$factory->state(\App\Models\TicketDepartment::class, 'internal', function (Faker $faker) {
    return [
        'name' => 'Internal - '.str_random(10),
        'internal' => 1,
    ];
});

$factory->state(\App\Models\TicketDepartment::class, 'external', function (Faker $faker) {
    return [
        'name' => 'External - '.str_random(10),
        'internal' => 0,
    ];
});
