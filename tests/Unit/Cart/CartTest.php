<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Cart\Money;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
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

    public function test_it_can_delete_the_item()
    {
        $cart = new Cart(
           $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create(), 
            ['quantity' => 1]
        );

        $cart->delete($product->id);

        $this->assertCount(0, $user->fresh()->cart);


    }

    public function test_it_empties_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
         );
 
         $user->cart()->attach(
             factory(ProductVariation::class)->create(), 
             ['quantity' => 1]
         );
 
         $cart->empty();
 
         $this->assertCount(0, $user->fresh()->cart);


    }

    public function test_it_can_check_the_cart_is_empty()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
         );
 
         $user->cart()->attach(
             factory(ProductVariation::class)->create(), [
                'quantity' => 0
             ]
         );

        $this->assertTrue($cart->isEmpty());

    }

    public function test_it_returns_a_money_instance_for_the_subtotal()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
         );

        $this->assertInstanceOf(Money::class, $cart->subtotal());

    }

    public function test_it_returns_the_correct_subtotal()
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

        $this->assertEquals($cart->subtotal()->amount(), 14000);

    }

    public function test_it_returns_a_money_instance_for_the_total()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
         );

        $this->assertInstanceOf(Money::class, $cart->total());

    }

    public function test_it_syncs_the_cart_to_the_updated_quantities()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
         );
 
         $product =  factory(ProductVariation::class)->create();
         $anohter_product =  factory(ProductVariation::class)->create();

         $user->cart()->attach([
             $product->id =>[
                'quantity' => 2
             ],
             $anohter_product->id =>[
                'quantity' => 2
             ]
         ]);

        $cart->sync();

        $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 0);
        $this->assertEquals($user->fresh()->cart->get(1)->pivot->quantity, 0);

    }

    public function test_it_shows_the_cart_has_changed_after_syncing()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
         );
 
         $product =  factory(ProductVariation::class)->create();
         $anohter_product =  factory(ProductVariation::class)->create();

         $user->cart()->attach([
             $product->id =>[
                'quantity' => 2
             ],
             $anohter_product->id =>[
                'quantity' => 2
             ]
         ]);


        $cart->sync();
        $this->assertTrue($cart->hasChanged());

    }

    public function test_it_shows_the_cart_has_not_change_if_there_was_no_syncing()
    {
        $cart = new Cart(
           factory(User::class)->create()
         );

        $cart->sync();
        $this->assertFalse($cart->hasChanged());

    }

    public function test_it_returns_the_correct_total_without_shipping()
    {
        $cart = new Cart(
           $user = factory(User::class)->create()
         );

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create([
                'price' => 1000
            ]),
            [
                'quantity' => 2
            ]
        );

        $this->assertEquals($cart->total()->amount(), 2000);

    }

    public function test_it_returns_the_correct_total_with_shipping()
    {
        $cart = new Cart(
           $user = factory(User::class)->create()
         );

         $shipping = factory(ShippingMethod::class)->create([
            'price' => 100
         ]);

        $user->cart()->attach(
            $product = factory(ProductVariation::class)->create([
                'price' => 1000
            ]),
            [
                'quantity' => 2
            ]
        );

        $cart->withShipping($shipping->id);

        $this->assertEquals($cart->total()->amount(), 2100);

    }

    public function test_it_returns_products_in_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );

        $user->cart()->attach(
            factory(ProductVariation::class)->create([
                'price' => 1000
            ]), [
                'quantity' => 2
            ]
        );

        $this->assertInstanceOf(ProductVariation::class, $cart->products()->first());
    }

}
