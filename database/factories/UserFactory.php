<?php

use Faker\Generator as Faker;

$factory->define(
    Socrates\Models\User::class, function (Faker $faker) {
        static $password;

        return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'telegram_id' => $faker->creditCardNumber,
        'username' => $faker->userName,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => \Illuminate\Support\Str::random(10),
        ];
    }
);
