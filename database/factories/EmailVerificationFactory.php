<?php

use App\Models\EmailVerification;
use Faker\Generator as Faker;

$factory->define(EmailVerification::class, function (Faker $faker) {
    return [
        'token' => Hash::make(str_random(40)),
    ];
});
