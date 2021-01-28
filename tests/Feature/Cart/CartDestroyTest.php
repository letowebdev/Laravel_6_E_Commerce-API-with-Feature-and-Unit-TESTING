<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CartDestroyTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_fails_to_destroy_if_the_user_is_unauthorized()
    {
        $this->json('DELETE', 'api/cart/1')
             ->assertStatus(401);
    }

    public function test_it_fails_to_destroy_if_product_doesnt_exist()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'DELETE', 'api/cart/999')
             ->assertStatus(404);
    }

    public function test_it_deletes_the_product_from_cart()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create() ,
            [
                'quantity' => $quantity = 7
            ]
        );

        $this->jsonAs($user, 'DELETE', "api/cart/{$product->id}");

        $this->assertDatabaseMissing('cart_user', [
            'product_variation_id' => $product->id,
            'quantity' =>  $quantity
        ]);
    }
}
