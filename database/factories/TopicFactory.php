<?php

use Faker\Generator as Faker;

/** @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->define(App\Models\Topic::class, function (Faker $faker) {

    $sentence = $faker->sentence();

    // 随机取一个月以内的时间
    $updatedAt = $faker->dateTimeThisMonth();
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $createdAt = $faker->dateTimeThisMonth($updatedAt);

    return [
        'title' => $sentence,
        'body' => $faker->text(),
        'excerpt' => $sentence,
        'created_at' => $createdAt,
        'updated_at' => $updatedAt,
    ];
});
