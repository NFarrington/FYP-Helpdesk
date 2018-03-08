<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\SlackWebhook::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'name' => $faker->sentence,
        'uri' => sprintf('https://hooks.slack.com/services/%s/%s/%s', str_random(8), str_random(9), str_random(24)),
        'recipient' => '#general',
    ];
});
