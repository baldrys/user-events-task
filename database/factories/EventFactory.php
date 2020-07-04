<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$names = ['Golf', 'Karaoke', 'Paintball', 'Wet T Shirt Contest'];

$factory->define(Event::class, function (Faker $faker) use ($names) {
    return [
        'name' => $names[array_rand($names)],
        'date' => $faker->date(),
        'city' => $faker->city,
    ];
});
