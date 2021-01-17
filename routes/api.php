<?php

use App\Category;

Route::resource('categories', 'Categories\CategoryController');
Route::resource('products', 'products\ProductController');
Route::get('products-list', 'products\ProductController@show_products_list')->name('products.list');