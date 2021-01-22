<?php

namespace App\Models;

use App\Models\Traits\CanBeFiltered;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use CanBeFiltered;
    
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class)->orderBy('order', 'asc');
    }
}