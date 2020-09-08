<?php

use Faker\Generator as Faker;

$factory->define(App\StaffCitDeduction::class, function (Faker $faker) {
    return [
        'staff_central_id' => function () {
            return factory(\App\StafMainMastModel::class)->create()->id;
        },
        'branch_id' => function () {
            return factory(\App\SystemOfficeMastModel::class)->create()->id;
        },
        'fiscal_year_id' => function () {
            return factory(\App\FiscalYearModel::class)->create()->id;
        },
        'month_id' => $faker->numberBetween(1, 12),
    ];
});
