<?php

use Faker\Generator as Faker;

$factory->define(App\SystemJobTypeMastModel::class, function (Faker $faker) {
    $effectDate = \Carbon\Carbon::now()->subYears(10)->toDateString();
    return [
        'jobtype_name' => $faker->sentence,
        'jobtype_code' => $faker->randomLetter,
        'effect_date' => $effectDate,
        'effect_date_np' => \App\Helpers\BSDateHelper::AdToBs('-', $effectDate),
        'gratuity' => $faker->numberBetween(10, 90),
        'profund_per' => $faker->numberBetween(10, 90),
        'profund_contri_per' => $faker->numberBetween(10, 90),
        'social_security_fund_per' => $faker->numberBetween(10, 90),
    ];
});
