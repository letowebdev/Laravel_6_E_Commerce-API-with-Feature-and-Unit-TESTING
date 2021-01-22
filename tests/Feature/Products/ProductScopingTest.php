<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_shows_a_list_of_products()
    {
        $products = factory(Product::class, 2)->create();

        // $this->json('GET', 'api/products')
        //      ->assertJsonFragment([
        //         $products->name,
        //      ]);

        $response = $this->json('GET', 'api/products');

        $products->each(function ($product) use ($response){
            $response->assertJsonFragment([
                $product->name,
            ]);
        });
    }

    public function test_it_has_pagination()
    {
        $this->json('GET', 'api/products')
             ->assertJsonStructure([
                 'links'
             ]);
    }
    
}
