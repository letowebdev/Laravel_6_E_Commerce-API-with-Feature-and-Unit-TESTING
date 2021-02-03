<?php

namespace Tests\Unit\Models\Users;

use App\Models\Address;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_hashes_the_password_when_created()
    {
        $user = factory(User::class)->create([
            'password' => 'my_cat'
         ]);

         $this->assertNotEquals($user->password, 'my_cat');

    }

    public function test_cart_has_a_collection_of_products()
    {
        $user = factory(User::class)->create();

        $user->cart()->save(
            factory(ProductVariation::class)->create(), [
                'quantity' => 1 
            ]
        );

        $this->assertInstanceOf(Collection::class, $user->cart);

    }

    public function test_it_has_a_quantity_for_each_cart_product()
    {
        $user = factory(User::class)->create();

        $user->cart()->save(
            factory(ProductVariation::class)->create(), [
                'quantity' => $quantity = 7 
            ]
        );

        $this->assertEquals($user->cart->first()->pivot->quantity, $quantity);

    }

    public function test_it_has_many_addresses()
    {
        $user = factory(User::class)->create();

        $user->addresses()->save(
            factory(Address::class)->make()
        );

        $this->assertInstanceOf(Address::class, $user->addresses->first());
    }

    public function test_it_has_many_orders()
    {
        $user = factory(User::class)->create();

        $user->orders()->save(
            factory(Order::class)->make()
        );

        $this->assertInstanceOf(Order::class, $user->orders->first());
    }

    
}
