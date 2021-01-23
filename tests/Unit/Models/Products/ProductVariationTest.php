<?php

namespace Tests\Unit\Models\Products;

use App\Cart\Money;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductVariationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_has_one_type()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(ProductVariationType::class, $variation->type);
        
    }

    public function test_it_belongs_to_a_product()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(Product::class, $variation->product);
        
    }

    public function test_it_returns_money_instance_for_the_price()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(Money::class, $variation->price);
    }

    public function test_it_returns_a_formatted_price()
    {
        $variation = factory(ProductVariation::class)->create([
            'price' => 7000
        ]);
        
        /* NumberFormatter adds a non-breaking space to it's output (which makes sense for currency)
        therefor there are two solutions to pass the test
        // faster solution
        $regular_spaces = str_replace("\xc2\xa0", ' ', $original_string);

        // more flexible solution
        $regular_spaces = preg_replace('/\xc2\xa0/', ' ', $original_string);
        */ 
        $this->assertEquals( preg_replace('/\xc2\xa0/', ' ', $variation->formattedPrice),'70,00 €');
    }

    public function test_it_returns_the_original_price_if_the_variation_price_is_null()
    {
        $product = factory(Product::class)->create([
            'price' => 7000
        ]);

        $variation = factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'price' => null
        ]);

        $this->assertEquals($product->price->amount(), $variation->price->amount());
    }

    public function test_it_checks_if_the_price_varies()
    {
        $product = factory(Product::class)->create([
            'price' => 7000
        ]);

        $variation = factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'price' => 3000
        ]);

        $this->assertTrue($variation->priceVaries);
    }



}
