<?php

use Faker\Generator as Faker;

$factory->define(App\AppVersion::class, function (Faker $faker) {
    return [
        'app_version_name' => $faker->sentence,
        'description' => $faker->paragraph,
        'path_name' => $faker->sentence
    ];
});
