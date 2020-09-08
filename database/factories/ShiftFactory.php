<?php

use Faker\Generator as Faker;

$factory->define(\App\Shift::class, function (Faker $faker) {
    return [
        'shift_name' => $faker->sentence,
        'branch_id' => function () {
            return factory(\App\SystemOfficeMastModel::class)->create()->office_id;
        },
        'punch_in' => '07:00:00',
        'before_punch_in_threshold' => 30,
        'after_punch_in_threshold' => 30,
        'punch_out' => '21:00:00',
        'before_punch_out_threshold' => 30,
        'after_punch_out_threshold' => 30,
        'min_tiffin_out' => '13:00:00',
        'max_tiffin_in' => '21:00:00',
        'tiffin_duration' => 30,
        'before_tiffin_threshold' => 30,
        'after_tiffin_threshold' => 30,
        'min_lunch_out' => '11:00:00',
        'max_lunch_in' => '12:00:00',
        'lunch_duration' => 30,
        'before_lunch_threshold' => 30,
        'after_lunch_threshold' => 30,
        'personal_in_out_duration' => 30,
        'personal_in_out_threshold' => 30,
        'parent_id' => null,
        'active' => 1,
    ];
});
