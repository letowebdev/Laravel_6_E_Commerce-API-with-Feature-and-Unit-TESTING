<?php

namespace Tests\Feature\Models\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_has_one_country()
    {
        //The country id is already included in the address factory
        $address = factory(Address::class)->create();

        $this->assertInstanceOf(Country::class, $address->country);
    }

    public function test_it_belongs_to_a_user()
    {
        //The country id is already included in the address factory
        $address = factory(Address::class)->create();

        $this->assertInstanceOf(User::class, $address->user);
    }

}
