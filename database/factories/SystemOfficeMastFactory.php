<?php

use App\SystemOfficeMastModel;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(SystemOfficeMastModel::class, function (Faker $faker) {
    return [
        'office_name' => $faker->company,
        'office_location' => $faker->address,
        'estd_date' => Carbon::parse('-2 years'),
        'sync' => 0,
    ];
});
