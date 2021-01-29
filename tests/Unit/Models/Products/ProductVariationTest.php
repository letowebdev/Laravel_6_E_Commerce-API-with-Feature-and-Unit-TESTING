<?php

namespace Tests\Unit\Models\Products;

use App\Cart\Money;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Collection;
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

    public function test_it_has_many_stocks()
    {
        $variation = factory(ProductVariation::class)->create();
        $variation->stocks()->save(
            factory(Stock::class)->create()
        );

        $this->assertInstanceOf(Collection::class, $variation->stocks);
    }

    public function test_it_has_stock_information()
    {
        $variation = factory(ProductVariation::class)->create();
        $variation->stocks()->save(
            factory(Stock::class)->create()
        );

        $this->assertInstanceOf(Collection::class, $variation->stock);
    }

    public function test_it_has_stock_count_pivot_within_stock_information()
    {
        $variation = factory(ProductVariation::class)->create();
        $variation->stocks()->save(
            factory(Stock::class)->create([
                'quantity' => $quantity = 177
            ])
        );

        $this->assertEquals($variation->stock->first()->pivot->stock, $quantity);
    }

    public function test_it_has_in_stock_pivot_within_stock_information()
    {
        $variation = factory(ProductVariation::class)->create();
        $variation->stocks()->save(
            factory(Stock::class)->create()
        );

        $this->assertTrue($variation->stock->first()->pivot->in_stock === 'true');
    }

    public function test_it_checks_if_it_is_in_stock()
    {
        $variation = factory(ProductVariation::class)->create();
        $variation->stocks()->save(
            factory(Stock::class)->create()
        );

        $this->assertTrue($variation->in_stock());
    }

    public function test_it_can_return_the_stock_count()
    {
        $variation = factory(ProductVariation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->create([
                'quantity' => 7
            ])
        );

        $variation->stocks()->save(
            factory(Stock::class)->create([
                'quantity' => 3
            ])
        );

        $this->assertEquals($variation->stockCount(), 10);
    }

    public function test_it_checks_minimum_stock()
    {
        $variation = factory(ProductVariation::class)->create();
        $variation->stocks()->save(
            factory(Stock::class)->create([
                'quantity' => $quantity = 5
            ])
        );

        $this->assertEquals($variation->minStock(700), $quantity);
    }


}
