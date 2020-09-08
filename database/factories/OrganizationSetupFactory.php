<?php

use Faker\Generator as Faker;

$factory->define(\App\OrganizationSetup::class, function (Faker $faker) {
    return [
        'organization_name' => $faker->sentence,
        'organization_address' => $faker->sentence,
        'organization_contact' => $faker->sentence,
        'organization_website' => $faker->sentence,
        'organization_email' => $faker->sentence,
        'organization_code' => 'BBSM',
        'organization_type' => 1,
        'organization_structure' => 1,
        'absent_weekend_on_cons_absent' => 1,
        'absent_publicholiday_on_cons_absent' => 1,
        'max_overtime_hour' => 10,
        'overtime_calculation_type' => 10,
    ];
});
