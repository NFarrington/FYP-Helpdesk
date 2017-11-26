<?php

use App\Models\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->paragraph,
    ];
});

$factory->state(Article::class, 'published', function (Faker $faker) {
    return [
        'visible_from' => \Carbon\Carbon::now()->subMinute(),
    ];
});

$factory->state(Article::class, 'unpublished', function (Faker $faker) {
    return [
        'visible_to' => \Carbon\Carbon::now()->subMinute(),
    ];
});
