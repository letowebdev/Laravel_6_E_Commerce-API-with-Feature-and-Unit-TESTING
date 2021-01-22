<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductFilteringTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_filters_by_category()
    {
        $category = factory(Category::class)->create();

        $category->products()->save(
           factory(Product::class)->create()
        );
      
        $this->json('GET', "api/products?category={$category->slug}")
             ->assertJsonCount(1, 'data');

        $another_category = factory(Category::class)->create();

        $this->json('GET', "api/products?category={$another_category->slug}")
        ->assertJsonCount(0, 'data');
    }

}
