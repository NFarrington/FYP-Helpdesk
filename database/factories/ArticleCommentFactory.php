<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ArticleComment::class, function (Faker $faker) {
    return [
        'content' => "{$faker->sentence}\n\n{$faker->sentence} {$faker->sentence}",
        'article_id' => function () {
            return factory(App\Models\Article::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
    ];
});
