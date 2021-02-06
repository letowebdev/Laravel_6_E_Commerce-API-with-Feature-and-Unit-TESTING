<?php

namespace Tests\Unit\Resources\Categories;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Foundation\Testing\Assert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryResourceTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_returns_correct_category_resource_data() :void
    {
        $resource = (new CategoryResource(
            $category = factory(Category::class)->create()
        ))->jsonSerialize();

        /**
         * Since assertArraySubset was deprecate in phpunit 8 and removed in PHPUnit 9
         * and no alternative was provided since then this was the only solution
         * provided by:
         * (https://github.com/rdohms/phpunit-arraysubset-asserts)
         */
        Assert::assertArraySubset([
            'name' => $category->name,
            'slug' => $category->slug,
        ], $resource);
    }
}
