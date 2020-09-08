<?php

use App\Caste;
use Faker\Generator as Faker;

$factory->define(Caste::class, function (Faker $faker) {
    return [
        'caste_name' => $faker->word
    ];
});
