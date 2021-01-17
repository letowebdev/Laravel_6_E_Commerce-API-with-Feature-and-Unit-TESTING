<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductScopingTest extends TestCase
{
    use DatabaseMigrations;
    public function test_it_can_scope_by_category()
    {
        $product = factory(Product::class)->create();

        $product->categories()->save(
            $category = factory(Category::class)->create()
        );

        $another_product = factory(Product::class)->create();
        $this->json('GET', "api/products?category={$category->slug}")
             ->assertJsonCount(1, 'data');
    }
}
