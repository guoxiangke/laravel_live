<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Models\Live;
use Faker\Generator as Faker;

$factory->define(Live::class, function (Faker $faker) {
    return [
    	'title' => $faker->name,
    	'description' => $faker->paragraph,
        'user_id' => function () {
            return factory(User::class)
                ->create()->id;
        },
        'start_at' => now(), //开始时间
        'during_minutes'=> $faker->randomNumber(2),//120
    ];
});
