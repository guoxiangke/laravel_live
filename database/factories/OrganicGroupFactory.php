<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\OrganicGroup;
use App\Models\Live;
use App\User;
use Faker\Generator as Faker;

$factory->define(OrganicGroup::class, function (Faker $faker) {
    return [
        'memberable_type' => function () {
            return User::class;
        },
        'memberable_id' => function () {
            return factory(User::class)
                ->create()->id;
        },
        'groupable_type' => function () {
            return Live::class;
        },
        'groupable_id' => function () {
            return factory(Live::class)
                ->create()->id;
        },
        'approved' => 0,
    ];
});
