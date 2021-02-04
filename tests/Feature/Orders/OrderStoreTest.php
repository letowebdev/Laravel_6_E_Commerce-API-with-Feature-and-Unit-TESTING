<?php

namespace Tests\Feature\Orders;

use App\Models\Address;
use App\Models\Country;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class OrderStoreTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_fails_to_store_an_order_if_not_authenitcated()
    {
        $this->json('POST', 'api/orders')
             ->assertStatus(401);
    }

    public function test_it_requires_an_address_id()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/orders')
             ->assertSee("The address id field is required.");
    }

    public function test_it_requires_an_address_that_exists()
    {
        $user = factory(User::class)->create();

        $this->be($user);

        $this->json('POST', 'api/orders', [
            'address_id' => 1
        ])->assertSee("The selected address id is invalid.");
    }

    public function test_it_requires_a_shipping_method_id()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'POST', 'api/orders')
             ->assertSee("The shipping method id field is required.");
    }

    public function test_it_requires_an_address_that_belongs_to_the_authenticated_user()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $address = factory(Address::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $this->json('POST', 'api/orders')
             ->assertSee("The address id field is required.");
                          
    }

    public function test_it_requires_a_shipping_method_that_exists()
    {
        $user = factory(User::class)->create();

        $this->be($user);

        $this->json('POST', 'api/orders', [
            'address_id' => 1
        ])->assertSee("The shipping method id field is required");
    }

    public function test_it_requires_a_shipping_method_that_is_valid_for_the_giving_address()
    {
        $user = factory(User::class)->create();

        $this->be($user);

        $country = factory(Country::class)->create();

        factory(Address::class)->create([
            'user_id' => $user->id,
            'country_id' => $country->id
        ]);

        factory(ShippingMethod::class)->create();

        $this->json('POST', 'api/orders', [
            'shipping_method_id' => 1
        ])->assertSee("Invalid shipping method");
    }

    public function test_it_stores_an_order()
    {
        $this->json('POST', 'api/orders', [
            $order = factory(Order::class)->create()
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $order->user_id,
            'shipping_method_id' => $order->shipping_method_id

        ]);
    }

    // public function test_it_attaches_the_products_to_the_order()
    // {   
    //     // TO DO 
    // }

    public function test_it_does_not_place_an_order_if_the_cart_is_empty()
    {
        $user = factory(User::class)->create();

        $user->cart()->sync([
            ($product = $this->productWithStock())->id => [
                'quantity' => 0
            ]
        ]);

        list($address, $shipping) = $this->orderDependencies($user);

        $response = $this->jsonAs($user, 'POST', 'api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id
        ])
            ->assertStatus(400);

        
    }


    protected function productWithStock()
    {
        $product = factory(ProductVariation::class)->create();

        factory(Stock::class)->create([
            'product_variation_id' => $product->id
        ]);

        return $product;
    }

    protected function orderDependencies(User $user)
    {

        $address = factory(Address::class)->create();

        $shipping = factory(ShippingMethod::class)->create();

        $shipping->countries()->attach($address->country);


        return [$address, $shipping];
    }


    
}
