<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Department::class, function (Faker $faker) {
    return [
        'name' => title_case(implode(' ', $faker->words(mt_rand(1, 3)))),
        'description' => $faker->sentence(),
        'internal' => $faker->numberBetween(0, 1),
    ];
});

$factory->state(\App\Models\Department::class, 'internal', function (Faker $faker) {
    return [
        'internal' => 1,
    ];
});

$factory->state(\App\Models\Department::class, 'external', function (Faker $faker) {
    return [
        'internal' => 0,
    ];
});
