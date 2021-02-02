<?php

namespace Tests\Unit\Models\Countries;

use App\Models\Country;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CountryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_belongs_to_many_shipping_methods()
    {
        $country = factory(Country::class)->create();

        $country->shippingMethods()->attach(
            factory(ShippingMethod::class)->create()
        );

        $this->assertInstanceOf(ShippingMethod::class, $country->shippingMethods->first());
    }

}
