<?php

namespace Tests\Unit;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_uses_the_slug_for_the_route_key_name()
    {
        $product = new Product();
        $this->assertEquals($product->getRouteKeyName(), 'slug');
    }
}
