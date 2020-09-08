<?php

use App\SystemOfficeMastModel;
use Faker\Generator as Faker;

$factory->define(\App\StafMainMastModel::class, function (Faker $faker) {
    $testingDate = \Carbon\Carbon::now()->subYear();
    $testingDateNp = \App\Helpers\BSDateHelper::AdToBs('-', $testingDate->toDateString());
    return [
        'name_eng' => $faker->name,
        'FName_Eng' => $faker->name,
        'gfname_eng' => $faker->name,
        'spname_eng' => $faker->name,
        'district_id' => 25,
        'ward_no' => $faker->numberBetween(10, 100),
        'tole_basti' => $faker->numberBetween(10, 100),
        'marrid_stat' => $faker->numberBetween(1, 2),
        'Gender' => $faker->numberBetween(1, 3),
        'edu_id' => function () {
            return factory(\App\Education::class)->create()->id;
        },
        'date_birth' => \Carbon\Carbon::now()->subYear(27),
        'branch_id' => function () {
            return factory(SystemOfficeMastModel::class)->create()->office_id;
        },
        'shift_id' => function () {
            return factory(\App\Shift::class)->create()->id;
        },
        'staff_type' => 0, //For BBSM
        'caste_id' => function () {
            return factory(\App\Caste::class)->create()->id;
        },
        'religion_id' => function () {
            return factory(\App\Religion::class)->create()->id;
        },
        'staff_status' => 1,
        'main_id' => $faker->numberBetween(1000000, 100000000),
        'staff_central_id' => $faker->numberBetween(100000, 10000000),
        'section' => function () {
            return factory(\App\Section::class)->create()->id;
        },
        'department' => function () {
            return factory(\App\Department::class)->create()->id;
        },
        'staff_dob' => $testingDateNp,
        'staff_citizen_no' => $faker->numberBetween(100000, 10000000),
        'staff_citizen_issue_office' => $faker->sentence,
        'staff_citizen_issue_date_np' => $testingDateNp,
        'phone_number' => $faker->phoneNumber,
        'emergency_phone_number' => $faker->phoneNumber,
        'post_id' => function () {
            return factory(\App\SystemPostMastModel::class)->create()->post_id;
        },
        'appo_date_np' => $testingDateNp,
        'jobtype_id' => function () {
            return factory(\App\SystemJobTypeMastModel::class)->create()->jobtype_id;
        }
    ];
});
