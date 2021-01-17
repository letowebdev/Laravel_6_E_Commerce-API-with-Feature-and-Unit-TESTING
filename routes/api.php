<?php

use App\Category;

Route::resource('categories', 'Categories\CategoryController');
Route::resource('products', 'products\ProductController');