<?php

use App\Category;

Route::get('/', function() {
   $categories = Category::parents()->ordered()->get();

   dd($categories);
});