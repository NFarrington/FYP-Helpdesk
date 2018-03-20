<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Role::class, function (Faker $faker) {
    return [
        'key' => str_random(),
        'name' => str_limit($faker->words(3, true).' - '.str_random(), 100, ''),
        'description' => $faker->sentence(),
    ];
});
