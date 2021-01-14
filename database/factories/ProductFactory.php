<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->unique()->product,
        'slug' => str::slug($name),
        'price' => $faker->numberBetween(1000, 6000),
        'description' => $faker->text,
    ];
});
