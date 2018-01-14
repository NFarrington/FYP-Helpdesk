<?php

use Faker\Generator as Faker;

$factory->define(League\OAuth2\Client\Provider\GoogleUser::class, function (Faker $faker) {
    $id = (string) mt_rand(mt_getrandmax()/2, mt_getrandmax());
    $firstName = $faker->firstName;
    $lastName = $faker->lastName;

    return [
        'emails' => [
            0 => [
                'value' => $faker->email,
            ],
        ],
        'id' => $id,
        'displayName' => "$firstName $lastName",
        'name' => [
            'familyName' => $lastName,
            'givenName' => $firstName,
        ],
        'image' => [
            'url' => $faker->imageUrl(),
        ]
    ];
});
