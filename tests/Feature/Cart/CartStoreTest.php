<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CartStoreTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_fails_if_the_user_is_unauthorized()
    {
        $this->json('POST', 'api/cart')
             ->assertStatus(401);
    }

    public function test_it_requires_products()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/cart')
             ->assertJsonMissingValidationErrors(['products']);
    }

    public function test_it_requires_products_to_be_an_array()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => 1
        ])
             ->assertJsonMissingValidationErrors(['products']);
    }

    public function test_it_requires_products_to_have_an_id()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['quantity' => 1]
            ]
        ])
             ->assertJsonMissingValidationErrors(['products.0.id']);
    }

    public function test_it_requires_products_to_exist()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['id' => 1, 'quantity' => 1]
            ]
        ])
             ->assertJsonMissingValidationErrors(['products.0.id']);
    }

    public function test_it_requires_products_quantity_to_be_numeric()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['id' => 1, 'quantity' => 'one']
            ]
        ])
             ->assertJsonMissingValidationErrors(['products.0.quantity']);
    }

    public function test_it_requires_products_quantity_to_be_at_least_one()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['id' => 1, 'quantity' => 0]
            ]
        ])
             ->assertJsonMissingValidationErrors(['products.0.quantity']);
    }

    public function test_it_can_add_products_to_cart()
    {
        $user = factory(User::class)->create();

        $product = factory(ProductVariation::class)->create();

        $this->jsonAs($user, 'POST', 'api/cart', [
            'products' => [
                ['id' => $product->id, 'quantity' => 9]
            ]
        ]);
        $this->assertDatabaseHas('cart_user', [
            'product_variation_id' => $product->id,
            'quantity' => 9
        ]);
    }
}
