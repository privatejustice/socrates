<?php

use Socrates\Models\Group;
use Socrates\Models\User;
use Faker\Generator as Faker;

$factory->define(
    Socrates\Models\Debt::class, function (Faker $faker) {
        return [
        'from_id' => factory(User::class)->create(),
        'to_id' => factory(User::class)->create(),
        'group_id' => factory(Group::class)->create(),
        'amount' => random_int(0, 200),
        'currency' => 'eur'
        ];
    }
);
