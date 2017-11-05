<?php

use App\Models\TicketStatus;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(TicketStatus::class, function (Faker $faker) {
    return [
        'name' => 'Random - '.str_random(10),
        'state' => Arr::random([
            TicketStatus::STATUS_AGENT,
            TicketStatus::STATUS_CUSTOMER,
            TicketStatus::STATUS_CLOSED,
        ]),
    ];
});

$factory->state(TicketStatus::class, 'agent', function (Faker $faker) {
    return [
        'name' => 'Agent - '.str_random(10),
        'state' => TicketStatus::STATUS_AGENT,
    ];
});

$factory->state(TicketStatus::class, 'customer', function (Faker $faker) {
    return [
        'name' => 'Customer - '.str_random(10),
        'state' => TicketStatus::STATUS_CUSTOMER,
    ];
});

$factory->state(TicketStatus::class, 'open', function (Faker $faker) {
    return [
        'name' => 'Open - '.str_random(10),
        'state' => Arr::random([
            TicketStatus::STATUS_AGENT,
            TicketStatus::STATUS_CUSTOMER,
        ]),
    ];
});

$factory->state(TicketStatus::class, 'closed', function (Faker $faker) {
    return [
        'name' => 'Closed - '.str_random(10),
        'state' => TicketStatus::STATUS_CLOSED,
    ];
});
