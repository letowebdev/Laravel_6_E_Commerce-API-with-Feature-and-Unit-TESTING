<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProductVariation;
use App\Models\Stock;
use Faker\Generator as Faker;

$factory->define(Stock::class, function (Faker $faker) {
    return [
        'product_variation_id' => factory(ProductVariation::class)->create()->id,
        'quantity' => 1,
    ];
});
