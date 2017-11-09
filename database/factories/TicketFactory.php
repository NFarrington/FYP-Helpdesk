<?php

use App\Models\Ticket;
use Faker\Generator as Faker;

$factory->define(Ticket::class, function (Faker $faker) {
    return [
        'department_id' => function () {
            return \App\Models\TicketDepartment::external()->inRandomOrder()->first()->id;
        },
        'status_id' => function () {
            return \App\Models\TicketStatus::inRandomOrder()->first()->id;
        },
        'user_id' => factory(App\Models\User::class)->create()->id,
        'summary' => $faker->sentence(),
    ];
});

$factory->state(Ticket::class, 'agent', function (Faker $faker) {
    return [
        'status_id' => \App\Models\TicketStatus::withAgent()->first()->id,
    ];
});

$factory->state(Ticket::class, 'customer', function (Faker $faker) {
    return [
        'status_id' => \App\Models\TicketStatus::withCustomer()->first()->id,
    ];
});

$factory->state(Ticket::class, 'open', function (Faker $faker) {
    return [
        'status_id' => \App\Models\TicketStatus::open()->first()->id,
    ];
});

$factory->state(Ticket::class, 'closed', function (Faker $faker) {
    return [
        'status_id' => \App\Models\TicketStatus::closed()->first()->id,
    ];
});
