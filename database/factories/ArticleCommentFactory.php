<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ArticleComment::class, function (Faker $faker) {
    return [
        'content' => $faker->sentence,
    ];
});
