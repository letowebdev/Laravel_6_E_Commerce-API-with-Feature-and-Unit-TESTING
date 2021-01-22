<?php

namespace Tests\Unit\Models\Products;

use App\Cart\Money;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_uses_the_slug_for_the_route()
    {
        $product = factory(Product::class)->create();

        $this->assertEquals($product->getRouteKeyName(), 'slug');
    }

    public function test_it_has_many_categories()
    {
        $product = factory(Product::class)->create();

        $product->categories()->save(
            factory(Category::class)->create()
        );

        $this->assertInstanceOf(Category::class, $product->categories->first());
    }

    public function test_it_has_variations()
    {
        $product = factory(Product::class)->create();

        $product->variations()->save(
            factory(ProductVariation::class)->create()
        );

        $this->assertInstanceOf(Collection::class, $product->variations);
    }

    public function test_it_returns_money_instance_for_the_price()
    {
        $product = factory(Product::class)->create();

        $this->assertInstanceOf(Money::class, $product->price);
    }

    public function test_it_returns_a_formatted_price()
    {
        $product = factory(Product::class)->create([
            'price' => 7000
        ]);
        
        /* NumberFormatter adds a non-breaking space to it's output (which makes sense for currency)
        therefor there are two solutions to pass the test
        // faster solution
        $regular_spaces = str_replace("\xc2\xa0", ' ', $original_string);

        // more flexible solution
        $regular_spaces = preg_replace('/\xc2\xa0/', ' ', $original_string);
        */ 
        $this->assertEquals( preg_replace('/\xc2\xa0/', ' ', $product->formattedPrice),'70,00 €');
    }
}


