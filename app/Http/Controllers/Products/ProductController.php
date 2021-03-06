<?php

namespace App\Http\Controllers\Products;

use App\Filtering\Filters\CategoryFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class productController extends Controller
{
    public function index()
    {
        return ProductIndexResource::collection(
            $products = Product::with('variations.stock')->withFilter($this->filters())->paginate(10)
        );     
    }

    public function show(Product $product)
    {
        // reducing queries
        $product->load([
            'variations.type',
            'variations.stock',
            'variations.product'
        ]);
        //returning product
        return new ProductResource(
            $product
        );
    }

    public function filters()
    {
        return [
            'category' => new CategoryFilter()
        ];
    }
}