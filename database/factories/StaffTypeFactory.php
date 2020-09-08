<?php

use Faker\Generator as Faker;

$factory->define(\App\StaffType::class, function (Faker $faker) {
    return [
        'staff_type_title' => $faker->sentence,
        'staff_type_code' => $faker->numberBetween(10, 100),
    ];
});
