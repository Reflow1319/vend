<?php

use App\User;

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'event_url'      => str_random(10),
        'password'       => 'secret',
        'remember_token' => str_random(10),
    ];
});
