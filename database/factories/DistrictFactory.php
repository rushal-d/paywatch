<?php

use App\District;
use Faker\Generator as Faker;

$factory->define(District::class, function (Faker $faker) {
    return [
        'district_id' => '25',
        'district_name_np' => $faker->sentence,
        'district_name' => $faker->sentence,
        'mun_vdc' => $faker->sentence,
        'type' => $faker->sentence,
        'province' => '3',
    ];
});
