<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PaymentMethod;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(PaymentMethod::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'cart_type' => 'VISA',
        'last_four' => 1234,
        'default' => true,
        'provider_id' => Str::random(5),
    ];
});
