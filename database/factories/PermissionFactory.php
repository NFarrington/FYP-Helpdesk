<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Permission::class, function (Faker $faker) {
    return [
        'key' => str_random(5).'.'.str_random(5),
        'name' => $faker->words(3, true),
        'description' => $faker->sentence(),
    ];
});
