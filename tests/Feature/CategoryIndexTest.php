<?php

namespace Tests\Feature;

use App\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryTest extends TestCase
{
  use DatabaseMigrations;

  public function test_it_returns_a_list_of_categories()
  {
      $categories = factory(Category::class, 2)->create();

      $response = $this->json('GET', 'api/categories');

      $categories->each(function($category) use ($response) {
        $response->assertJsonFragment([
            'slug' => $category->slug,
        ]);
      });
           
  }

  public function test_it_returns_only_parent_category()
  {
    $category = factory(Category::class)->create();

    $category->children()->save(
        factory(Category::class)->create()
    );

    $this->json('GET', 'api/categories')
         ->assertJsonCount(1, 'data');
  }

  public function test_it_returns_categories_in_their_givien_order()
  {
    $category = factory(Category::class)->create([
        'order' => 2
    ]);

    $antoher_category = factory(Category::class)->create([
        'order' => 1
    ]);

    $this->json('GET', 'api/categories')
         ->assertSeeInOrder([
            $antoher_category->slug,
            $category->slug
         ]);
  }




}
