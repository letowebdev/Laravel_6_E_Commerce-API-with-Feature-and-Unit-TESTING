<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CartIndexTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_fails_to_load_cart_items_if_unauthenticated()
    {

        $this->json('GET', 'api/cart')
             ->assertStatus(401);
    }

    public function test_it_shows_products_in_the_users_cart()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(),
            [
                'quantity' => 1
            ]
        );

        $this->jsonAs($user, 'GET', 'api/cart')
             ->assertJsonFragment([
                'id' => $product->id
             ]);
    }
}
