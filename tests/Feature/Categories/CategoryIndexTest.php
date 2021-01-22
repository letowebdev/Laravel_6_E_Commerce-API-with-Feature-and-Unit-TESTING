<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryIndexTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_has_a_collection_of_categories()
    {
        $Categories = factory(Category::class, 2)->create();

        $response = $this->json('GET', 'api/categories');

        $Categories->each(function($category) use ($response){
            $response->assertJsonFragment([
                'slug' => $category->slug,
             ]);
        });
        
    }

    public function test_it_returns_parents_only()
    {
        $category = factory(Category::class)->create();
        $category->children()->save(
            factory(Category::class)->create()
        );

        $this->json('GET', 'api/categories')
             ->assertJsonCount(1, 'data');

    }


    public function test_it_returns_orderable_categories()
    {
        $category = factory(Category::class)->create([
            'order' => 2
        ]);

        $another_category = factory(Category::class)->create([
            'order' => 1
        ]);
    
        $this->json('GET', 'api/categories')
             ->assertSeeInOrder([$another_category->name, $category->name]);
    }

}
