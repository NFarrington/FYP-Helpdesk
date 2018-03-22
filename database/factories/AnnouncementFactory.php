<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Announcement::class, function (Faker $faker) {
    return [
        'title' => title_case($faker->sentence),
        'content' => sprintf(
            "# %s\n\n### %s\n\n%s",
            implode(' ', $faker->words(3)),
            implode(' ', $faker->words(4)),
            $faker->paragraph
        ),
        'status' => \App\Models\Announcement::STATUS_UNPUBLISHED,
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
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
