<?php

namespace Tests\Unit\Models\Orders;

use App\Cart\Money;
use App\Models\Address;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_has_a_default_status()
    { 
        $order = factory(Order::class)->create();

        $this->assertEquals($order->status, Order::PENDING);
    }

    public function test_it_belongs_to_a_user()
    {

        $order = factory(Order::class)->create();


        $this->assertInstanceOf(User::class, $order->user);
    }

    public function test_it_belongs_to_an_address()
    {

        $order = factory(Order::class)->create();


        $this->assertInstanceOf(Address::class, $order->address);
    }

    public function test_it_belongs_to_a_shipping_method()
    {

        $order = factory(Order::class)->create();


        $this->assertInstanceOf(ShippingMethod::class, $order->shipping_method);
    }

    public function test_it_has_many_product_variations()
    {
        $order = factory(Order::class)->create();
        $order->products()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => 2
            ]
        );

        $this->assertInstanceOf(ProductVariation::class, $order->products->first());

    }

    public function test_it_can_have_a_quantity_attach_to_the_product()
    {
        $order = factory(Order::class)->create();
        $order->products()->attach(
            factory(ProductVariation::class)->create(), [
                'quantity' => $quantity = 2
            ]
        );

        $this->assertEquals($order->products->first()->pivot->quantity, $quantity);

    }

    public function test_it_returns_a_money_instance_for_the_subtotal_in_order()
    {
        $order = factory(Order::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $this->assertInstanceOf(Money::class, $order->subtotal);
    }

    public function test_it_returns_a_money_instance_for_the_total_in_order()
    {
        $order = factory(Order::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $this->assertInstanceOf(Money::class, $order->total());
    }

    public function test_it_adds_shipping_onto_the_total_in_order()
    {
        $order = factory(Order::class)->create([
            'user_id' => factory(User::class)->create()->id,
            'subtotal' => 1000,
            'shipping_method_id' => factory(ShippingMethod::class)->create([
                'price' => 1000
            ])
        ]);

        $this->assertEquals($order->total()->amount(), 2000);
    }



}
