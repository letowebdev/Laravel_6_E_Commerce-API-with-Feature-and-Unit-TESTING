<?php

namespace Tests\Unit\Resources\Products;

use App\Http\Resources\ProductIndexResource;
use App\Models\Product;
use Illuminate\Foundation\Testing\Assert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductIndexResourceTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_returns_correct_data() :void
    {
        $resource = (new ProductIndexResource(
            $product = factory(Product::class)->create()
        ))->jsonSerialize();

        /**
         * Since assertArraySubset was deprecate in phpunit 8 and removed in PHPUnit 9
         * and no alternative was provided since then this was the only solution
         * provided by:
         * (https://github.com/rdohms/phpunit-arraysubset-asserts)
         */
        Assert::assertArraySubset([
            'id' => $product->id,
            'slug' => $product->slug,
            'price' => $product->formattedPrice,
        ], $resource);
    }


}
