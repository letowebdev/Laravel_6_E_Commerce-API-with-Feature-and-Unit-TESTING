<?php

namespace Tests\Feature\Orders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class OrderIndexTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_fails_to_load_order_response_if_not_authenticated()
    {
        $this->json('GET', 'api/orders')
             ->assertStatus(401);
    }

    public function test_it_returns_a_collection_of_orders()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $order = factory(Order::class)->create([
            'user_id' => $user->id
        ]);

        $this->json('GET', 'api/orders')
             ->assertJsonFragment([
             'id' => $order->id
              ]);

    }

    public function test_it_returns_pagination()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        factory(Order::class)->create([
            'user_id' => $user->id
        ]);

        $this->json('GET', 'api/orders')
             ->assertJsonStructure([
                'links'
              ]);

    }

    public function test_it_returns_orders_by_latest()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $order = factory(Order::class)->create([
            'user_id' => $user->id
        ]);

        $another_order = factory(Order::class)->create([
            'user_id' => $user->id,
            'created_at' => now()
        ]);

        $this->json('GET', 'api/orders')
             ->assertSeeInOrder([
                $order->created_at->toDateTimeString(),
                $another_order->created_at->toDateTimeString()
              ]);

    }
}
