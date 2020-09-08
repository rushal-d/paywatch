<?php

use App\PublicHoliday;
use App\SystemOfficeMastModel;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(PublicHoliday::class, function (Faker $faker) {
    return [
//        'branch_id' => factory(SystemOfficeMastModel::class)->create()->id,
        'branch_id' => 1,
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'from_date' => Carbon::yesterday(),
        'from_date_np' => Carbon::yesterday(),
        'gender' => 0,
        'to_date' => Carbon::now(),
        'to_date_np' => Carbon::now(),
        'created_by' => 1,
        'updated_by' => 1
    ];
});
