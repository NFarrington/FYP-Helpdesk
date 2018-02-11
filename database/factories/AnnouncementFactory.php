<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Announcement::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->paragraph,
        'status' => mt_rand(0, 2),
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        }
    ];
});

$factory->state(App\Models\Announcement::class, 'active', function (Faker $faker) {
    return [
        'status' => \App\Models\Announcement::STATUS_ACTIVE,
    ];
});

$factory->state(App\Models\Announcement::class, 'published', function (Faker $faker) {
    return [
        'status' => \App\Models\Announcement::STATUS_PUBLISHED,
    ];
});

$factory->state(App\Models\Announcement::class, 'unpublished', function (Faker $faker) {
    return [
        'status' => \App\Models\Announcement::STATUS_UNPUBLISHED,
    ];
});
