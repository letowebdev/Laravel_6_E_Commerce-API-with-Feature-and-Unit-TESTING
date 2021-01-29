<?php

namespace Tests\Feature\Cart;

use App\Cart\Cart;
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

     public function test_it_shows_if_the_cart_is_empty()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'GET', 'api/cart')
             ->assertJsonFragment([
                'empty' => true
             ]);
    }

    public function test_it_shows_a_formatted_subTotal()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
         );
 
         $user->cart()->attach(
             factory(ProductVariation::class)->create([
                 'price' => 7000
             ]), [
                'quantity' => 2
             ]
         );
        
        $this->assertEquals( str_replace("\xc2\xa0", ' ',  $cart->subTotal()->formatted()),'140,00 €');
    }

    public function test_it_shows_a_formatted_total()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
         );
 
         $user->cart()->attach(
             factory(ProductVariation::class)->create([
                 'price' => 7000
             ]), [
                'quantity' => 3
             ]
         );
        
        $this->assertEquals( str_replace("\xc2\xa0", ' ',  $cart->total()->formatted()),'210,00 €');
    }

    public function test_it_syncs_the_cart()
    {
        $user = factory(User::class)->create();

        $user->cart()->attach(
            factory(ProductVariation::class)->create(), [
               'quantity' => 2
            ]
        );

        $this->jsonAs($user, 'GET', 'api/cart')
             ->assertJsonFragment([
                'changed' => true
             ]);
    }

    public function test_it_does_not_sync_if_there_were_no_changes()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'GET', 'api/cart')
             ->assertJsonFragment([
                'changed' => false
             ]);
    }
}
