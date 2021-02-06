<?php

namespace Tests\Unit\Resources\Addresses;

use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Foundation\Testing\Assert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddressResourceTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_returns_correct_address_data() :void
    {
        $resource = (new AddressResource(
            $address = factory(Address::class)->create()
        ))->jsonSerialize();

        /**
         * Since assertArraySubset was deprecate in phpunit 8 and removed in PHPUnit 9
         * and no alternative was provided since then this was the only solution
         * provided by:
         * (https://github.com/rdohms/phpunit-arraysubset-asserts)
         */
        Assert::assertArraySubset([
            'id' => $address->id,
            'name' => $address->name,
            'address_1' => $address->address_1,
            'city' => $address->city,
            'postal_code' => $address->postal_code
        ], $resource);
    }
}
