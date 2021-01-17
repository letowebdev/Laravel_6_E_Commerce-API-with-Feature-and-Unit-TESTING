<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_shows_a_list_of_products()
    {
        
      $products = factory(Product::class, 2)->create();

      $response = $this->json('GET', 'api/products-list');

      $products->each(function($product) use ($response) {
        $response->assertJsonFragment([
            'slug' => $product->slug,
        ]);
      });
    }

    public function test_it_has_paginated_data()
    {
        $this->json('GET', 'api/products')
             ->assertJsonStructure([
                'links',
             ]);
    }

}
