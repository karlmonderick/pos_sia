<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Inventory;
use Faker\Generator as Faker;

$factory->define(Inventory::class, function (Faker $faker) {
    return [
        'product_id' => $faker->numberBetween($min = 1, $max = 10),
        'stock_quantity' => $faker->numberBetween($min = 1, $max = 50),
        'user_id' => $faker->numberBetween($min = 1, $max = 4),

    ];
});
