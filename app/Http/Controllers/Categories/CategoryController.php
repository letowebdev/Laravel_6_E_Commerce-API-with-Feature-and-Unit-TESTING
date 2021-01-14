<?php

namespace App\Http\Controllers\Categories;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(
            Category::with('children')->parents()->ordered()->get()
        );        
    }
}
