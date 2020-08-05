<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Ticket\Model\Ticket;

$factory->define(Ticket::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'title' => $faker->title,
        'description' => $faker->text,
    ];
});
