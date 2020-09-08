<?php

use Faker\Generator as Faker;

$factory->define(\App\SystemPostMastModel::class, function (Faker $faker) {
    $effectDate = \Carbon\Carbon::now()->subYear();
    return [
        'post_title' => $faker->sentence,
        'basic_salary' => $faker->numberBetween(1000, 10000),
        'effect_date' => $effectDate,
        'effect_date_np' => \App\Helpers\BSDateHelper::AdToBs('-', $effectDate->toDateString()),
        'active' => \App\SystemPostMastModel::ACTIVE_STATUS,
    ];
});
