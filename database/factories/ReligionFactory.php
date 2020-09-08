<?php

use App\Religion;
use Faker\Generator as Faker;

$factory->define(Religion::class, function (Faker $faker) {
    return [
        'religion_name' => $faker->name,
        'description' => $faker->paragraph
    ];
});
