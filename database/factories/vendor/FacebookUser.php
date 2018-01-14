<?php

use Faker\Generator as Faker;

$factory->define(League\OAuth2\Client\Provider\FacebookUser::class, function (Faker $faker) {
    $id = (string) mt_rand(mt_getrandmax()/2, mt_getrandmax());
    $firstName = $faker->firstName;
    $lastName = $faker->lastName;

    return [
        'id' => $id,
        'name' => "$firstName $lastName",
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $faker->email,
        'picture' => [
            'data' => [
                'url' => 'url_to_jpg',
                'is_silhouette' => false,
            ],
        ],
        'cover' => [
            'source' => 'url_to_jpg',
            'id' => mt_rand(),
        ],
        'gender' => array_random(['male', 'female']),
        'locale' => 'en_GB',
        'link' => "https://www.facebook.com/app_scoped_user_id/{$id}/",
        'timezone' => 0,
        'age_range' => [
            'min' => 18,
        ],
    ];
});

$factory->state(League\OAuth2\Client\Provider\FacebookUser::class, 'no email', function (Faker $faker) {
    return [
        'email' => null,
    ];
});
