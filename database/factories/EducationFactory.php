<?php

use App\Education;
use Faker\Generator as Faker;

$factory->define(Education::class, function (Faker $faker) {
    return [
        'edu_description' => $faker->paragraph
    ];
});
