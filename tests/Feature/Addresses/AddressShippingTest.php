<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddressShippingTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_fails_to_load_address_shipping_if_not_authorized()
    {
        $this->json('GET', 'api/addresses/1/shipping')
             ->assertStatus(401);
    }

    public function test_fails_if_the_address_cant_be_found()
    {
        $user = factory(User::class)->create();
        $this->be($user);
      
        $this->json('GET', 'api/addresses/1/shipping')
             ->assertStatus(401);
    }

    public function test_fails_if_the_address_does_not_belong_to_the_authenticated_user()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $address = factory(Address::class)->create();
        

        $this->json('GET', "api/addresses/{$address->id}/shipping")
             ->assertStatus(401);
    }

    public function test_it_shows_the_shipping_addresses_to_the_authenticated_user()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $address = factory(Address::class)->create([
            'user_id' => $user->id,
            'country_id' => ($country = factory(Country::class)->create())->id
        ]);

        $country->shippingMethods()->save(
            $shipping = factory(ShippingMethod::class)->create()
        );
        

        $this->json('GET', "api/addresses/{$address->id}/shipping")
             ->assertJsonFragment([
                'id' => $shipping->id
             ]);
    }
}
