<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->domainWord(),
        'price' => $faker->numberBetween($min = 1, $max = 50),
        'description' => $faker->paragraphs(rand(2, 10), true),
    ];
});
