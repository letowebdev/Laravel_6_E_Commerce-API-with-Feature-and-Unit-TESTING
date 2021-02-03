<?php

namespace Tests\Unit\Models\Orders;

use App\Models\Address;
use App\Models\Order;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseMigrations;

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

}
