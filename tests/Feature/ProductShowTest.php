<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductShowTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_fails_if_a_product_does_not_exist()
    {
        $this->json('GET', 'api/products/nope')
             ->assertStatus(404);
    }

    public function test_it_shows_a_product()
    {
        $product = factory(Product::class)->create();

        $this->json('GET', "api/products/{$product->slug}")
             ->assertJsonFragment([
                 'id' => $product->id,
             ]);
    }

}
