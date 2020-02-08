<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Models\Rrule;
use Faker\Generator as Faker;

$factory->define(Rrule::class, function (Faker $faker) {
    return [
    	'start_at' => now(),
    	'end_at' => now(),
        'rrule_string' => 'RRULE:FREQ=DAILY;COUNT=1;INTERVAL=1;WKST=MO;BYDAY=MO,TU,WE,TH,FR,SA,SU',
    ];
});
