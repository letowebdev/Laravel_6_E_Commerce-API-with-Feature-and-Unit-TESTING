<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CartUpdateTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_fails_if_unauthenticated()
    {

        $this->json('PATCH', 'api/cart/1')
             ->assertStatus(401);
    }

    public function test_it_fails_if_product_doesnt_exist()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'PATCH', 'api/cart/999')
             ->assertStatus(404);
    }

    public function test_it_requires_the_quantity()
    {
        $user = factory(User::class)->create();

        $product = factory(ProductVariation::class)->create();

        $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}")
             ->assertSee('The quantity field is required');
    }

    public function test_it_requires_a_numeric_quantity()
    {
        $user = factory(User::class)->create();

        $product = factory(ProductVariation::class)->create();

        $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}", [
            'quantity' => 'one'
        ])
             ->assertSee('The quantity must be a number');
    }

    public function test_it_requires_a_quantity_of_at_least_one()
    {
        $user = factory(User::class)->create();

        $product = factory(ProductVariation::class)->create();

        $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}", [
            'quantity' => 0
        ])
             ->assertSee('The quantity must be at least 1');
    }

    public function test_it_updates_the_quantity()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(), [
                'quantity' => 1
            ]
        );

        $this->jsonAs($user, 'PATCH', "api/cart/{$product->id}", [
            'quantity' => $quantity = 9
        ]);

        $this->assertDatabaseHas('cart_user', [
            'product_variation_id' => $product->id,
            'quantity' => $quantity
        ]);
    }
}
