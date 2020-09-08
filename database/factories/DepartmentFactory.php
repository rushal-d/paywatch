<?php

use App\Department;
use Faker\Generator as Faker;

$factory->define(Department::class, function (Faker $faker) {
    return [
        'department_name' => $faker->sentence
    ];
});
