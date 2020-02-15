<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Models\Message;
use App\Models\Live;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
    	'message' => $faker->paragraph,
        'user_id' => function () {
            return factory(User::class)
                ->create()->id;
        },
        'messageable_type' => function () {
            return Live::class;
        },
        'messageable_id' => function () {
            return factory(Live::class)
                ->create()->id;
        },
    ];
});
