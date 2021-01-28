<?php

namespace Tests\Unit\Resources\Products;

use App\Http\Resources\ProductVariationResource;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\Assert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductVariationResourceTest extends TestCase
{  
    use DatabaseMigrations;

    public function test_it_returns_correct_product_data() :void
    {
        $resource = (new ProductVariationResource(
            $product = factory(ProductVariation::class)->create()
        ))->jsonSerialize();

        /**
         * Since assertArraySubset was deprecate in phpunit 8 and removed in PHPUnit 9
         * and no alternative was provided since then this was the only solution
         * provided by:
         * (https://github.com/rdohms/phpunit-arraysubset-asserts)
         */
        Assert::assertArraySubset([
            'id' => $product->id,
            'price_varies'=> $product->priceVaries
        ], $resource);
    }
 
}
