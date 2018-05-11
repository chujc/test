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

$i = 0;

$factory->define(App\Models\User::class, function (Faker $faker) use (&$i){


    $array = [
        'level' => rand(0, 2),
        'parent_id' => rand(0, $i),
        'monthly_income' => rand(0, 10000)
    ];

    $i++;

    return  $array;
});
