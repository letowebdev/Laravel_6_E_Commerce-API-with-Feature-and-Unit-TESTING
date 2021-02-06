<?php

namespace Tests\Unit\Resources\Countires;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Foundation\Testing\Assert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CountyResourceTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_returns_correct_country_resource_data() :void
    {
        $resource = (new CountryResource(
            $country = factory(Country::class)->create()
        ))->jsonSerialize();

        /**
         * Since assertArraySubset was deprecate in phpunit 8 and removed in PHPUnit 9
         * and no alternative was provided since then this was the only solution
         * provided by:
         * (https://github.com/rdohms/phpunit-arraysubset-asserts)
         */
        Assert::assertArraySubset([
            'id' => $country->id,
            'name' => $country->name,
            'code' => $country->code,
        ], $resource);
    }
}
