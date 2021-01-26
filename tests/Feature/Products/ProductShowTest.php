<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductShowTest extends TestCase
{
   use DatabaseMigrations;

   public function test_it_fails_when_there_is_no_product()
   {
        $this->json('GET', 'api/products/it-doesnt-exist')
             ->assertStatus(404);
   }

   public function test_it_shows_a_product()
   {
       $product = factory(Product::class)->create();

       $this->json('GET', "api/products/{$product->slug}")
            ->assertJsonFragment([
                $product->name,
            ]);
   }
}
