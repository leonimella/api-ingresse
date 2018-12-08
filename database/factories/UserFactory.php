<?php

use Faker\Generator as Faker;

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

$brazilianStates = [
    'AC',
    'AL',
    'AP',
    'AM',
    'BA',
    'CE',
    'DF',
    'ES',
    'GO',
    'MA',
    'MT',
    'MS',
    'MG',
    'PA',
    'PB',
    'PR',
    'PE',
    'PI',
    'RJ',
    'RN',
    'RS',
    'RO',
    'RR',
    'SC',
    'SP',
    'TO',
    'SE'
];

$factory->define(App\User::class, function (Faker $faker) use ($brazilianStates) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'country' => $faker->country,
        'state' => $brazilianStates[rand(0, count($brazilianStates) -1)],
        'city' => $faker->city,
        'address' => $faker->streetAddress,
        'number' => $faker->numberBetween(1, 999),
        'zipcode' => $faker->postcode
    ];
});
