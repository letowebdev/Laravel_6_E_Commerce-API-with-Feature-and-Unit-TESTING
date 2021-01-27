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

}
