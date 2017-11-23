<?php

use Faker\Generator as Faker;

/** @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->define(App\Models\Reply::class, function (Faker $faker) {
    $time = $faker->dateTimeThisMonth();

    return [
        'content' => $faker->sentence(),
        'created_at' => $time,
        'updated_at' => $time,
    ];
});
