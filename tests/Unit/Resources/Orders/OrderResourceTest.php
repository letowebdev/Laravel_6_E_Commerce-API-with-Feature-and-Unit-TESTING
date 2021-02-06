<?php

namespace Tests\Unit\Resources\Orders;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Foundation\Testing\Assert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class OrderResourceTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_returns_correct_order_resource_data() :void
    {
        $resource = (new OrderResource(
            $order = factory(Order::class)->create()
        ))->jsonSerialize();

        /**
         * Since assertArraySubset was deprecate in phpunit 8 and removed in PHPUnit 9
         * and no alternative was provided since then this was the only solution
         * provided by:
         * (https://github.com/rdohms/phpunit-arraysubset-asserts)
         */
        Assert::assertArraySubset([
            'id' => $order->id,
            'status' => $order->status,
            'created_at' => $order->created_at->toDateTimeString(),
            'subtotal' => $order->subtotal->formatted(),
            'total' => $order->total()->formatted(),
        ], $resource);
    }
}
