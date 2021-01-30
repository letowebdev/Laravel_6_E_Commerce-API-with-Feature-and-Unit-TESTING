<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_fails_to_load_addresses_if_not_authenticated()
    {
        $this->json('GET', 'api/addresses')
             ->assertStatus(401);
    }

    public function test_it_returns_addresses_if_authenticated()
    {
        $user = factory(User::class)->create();

        $user->addresses()->save(
            $address = factory(Address::class)->create()
        );

        $this->jsonAs($user, 'GET', 'api/addresses')
             ->assertJsonFragment([
                'id' => $address->id,
                'city' => $address->city
             ]);
    }
}
