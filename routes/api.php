<?php

Route::resource('categories', 'Categories\CategoryController');
Route::resource('products', 'products\ProductController');

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'Auth\RegisterController@action');
});