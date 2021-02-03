<?php

namespace Tests\Feature\Orders;

use App\Models\Address;
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

    
}
