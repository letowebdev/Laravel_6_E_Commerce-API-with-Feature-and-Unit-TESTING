<?php

namespace Tests\Unit\Models\ShippingMehtods;

use App\Cart\Money;
use App\Models\Country;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ShippingMethodTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_returns_a_money_instance_for_the_shipping_price()
    {
        $shipping = factory(ShippingMethod::class)->create();

        $this->assertInstanceOf(Money::class, $shipping->price);
    }

    public function test_it_returns_a_formatted_price_for_the_shipping()
    {
        $shipping = factory(ShippingMethod::class)->create([
            'price' => 15000,
        ]);

        $this->assertEquals( preg_replace('/\xc2\xa0/', ' ', $shipping->formattedPrice), '150,00 €');
    }

    public function test_it_belongs_to_many_countries()
    {
        $shipping = factory(ShippingMethod::class)->create();

        $shipping->countries()->attach(
            factory(Country::class)->create()
        );

        $this->assertInstanceOf(Country::class, $shipping->countries->first());
    }

    
}
