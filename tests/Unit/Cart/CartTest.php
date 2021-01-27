<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CartTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_adds_products_to_the_cart()
    {
        $cart = new Cart(
           $user = factory(User::class)->create()
        );

        $product = factory(ProductVariation::class)->create();

        $cart->add([
            [
            'id' => $product->id,
            'quantity' => 7
            ]
        ]);

        $this->assertCount(1, $user->fresh()->cart);
    }

    public function test_it_adds_it_increments_products_when_added()
    {
        $product = factory(ProductVariation::class)->create();
        
        $cart = new Cart(
           $user = factory(User::class)->create()
        );

        $cart->add([
            [
            'id' => $product->id,
            'quantity' => 1
            ]
        ]);

        $cart = new Cart($user->fresh());

        $cart->add([
            [
            'id' => $product->id,
            'quantity' => 2
            ]
        ]);

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 3);
    }

    public function test_it_can_update_quantity()
    {
        $cart = new Cart(
           $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(), 
            ['quantity' => 1]
        );

        $cart->update($product->id, 7);

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 7);


    }


}
