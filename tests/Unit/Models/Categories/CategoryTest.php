<?php

namespace Tests\Unit\Models\Categories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_has_many_children()
    {
        $categories = factory(Category::class)->create();

        $categories->children()->save(
            factory(Category::class)->create()
        );

        $this->assertInstanceOf(Category::class, $categories->children->first());
    }

    public function test_it_can_fetch_only_parents()
    {
        $categories = factory(Category::class)->create();

        $categories->children()->save(
            factory(Category::class)->create()
        );

        $this->assertCount(1, [Category::parents()]);
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

    public function test_it_belongs_to_many_products()
    {
        $category =  factory(Category::class)->create();

        $category->products()->save(
            factory(Product::class)->create()
        );

        $this->assertInstanceOf(Product::class, $category->products->first());
    }
}
