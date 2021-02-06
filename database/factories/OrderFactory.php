<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Address;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'address_id' => factory(Address::class)->create()->id,
        'shipping_method_id' => factory(ShippingMethod::class)->create()->id,
        'payment_method_id' => factory(PaymentMethod::class)->create()->id,
    ];
});
