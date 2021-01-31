<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddressStoreTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_fails_to_Add_addresses_if_not_authenticated()
    {
        $this->json('POST', 'api/addresses')
             ->assertStatus(401);
    }

    public function test_it_requires_a_name()
    {
        $user = factory(User::class)->create();
        $this->be($user);
        $this->json('POST', 'api/addresses')
             ->assertSee('The name field is required.');
    }

    public function test_it_requires_an_address()
    {
        $user = factory(User::class)->create();
        $this->be($user);
        $this->json('POST', 'api/addresses')
             ->assertSee('The postal code field is required.');
    }

    public function test_it_requires_a_city()
    {
        $user = factory(User::class)->create();
        $this->be($user);
        $this->json('POST', 'api/addresses')
             ->assertSee('The city field is required.');
    }

    public function test_it_requires_a_postal_code()
    {
        $user = factory(User::class)->create();
        $this->be($user);
        $this->json('POST', 'api/addresses')
             ->assertSee('The postal code field is required.');
    }

    public function test_it_requires_a_country_id()
    {
        $user = factory(User::class)->create();
        $this->be($user);
        $this->json('POST', 'api/addresses')
             ->assertSee('The country id field is required.');
    }

    public function test_it_stores_an_address()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $this->json('POST', 'api/addresses', [
            $address = factory(Address::class)->create()
            ]);

        $this->assertDatabaseHas('addresses', [
            'name' => $address->name,
            'address_1' => $address->address_1,
            'city' => $address->city,
            'postal_code' => $address->postal_code,
            'country_id' => $address->country_id

        ]);
    }

    public function test_it_returns_a_json_response_with_data()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $reponse = $this->json('POST', 'api/addresses', [
            $address = factory(Address::class)->create()
            ]);

        $reponse->assertJsonFragment([
            'name' => json_decode($reponse->getContent())->name,
            'city' => json_decode($reponse->getContent())->city
        ]); 
    }
}
