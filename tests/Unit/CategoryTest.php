<?php

namespace Tests\Unit;

use App\Category;
use App\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_has_many_children()
    {
        $category = factory(Category::class)->create();

        $category->children()->save(
            factory(Category::class)->create()
        );

        $this->assertInstanceOf(Category::class, $category->children->first());
    }

    public function test_it_can_fetch_only_parents()
    {
        $category = factory(Category::class)->create();

        $category->children()->save(
            factory(Category::class)->create()
        );

        $this->assertEquals(1, Category::parents()->count());
    }

    public function test_it_is_orderable()
    {
        $category = factory(Category::class)->create([
            'order' => 2,
        ]);

        $another_category = factory(Category::class)->create([
            'order' => 1,
        ]);

        $this->assertEquals($another_category->name, Category::ordered()->first()->name);
    }

    public function test_it_has_many_products()
    {
        $category = factory(Category::class)->create();

        $category->products()->save(
            factory(Product::class)->create()
        );

        $this->assertInstanceOf(Collection::class, $category->products);
    }






}
