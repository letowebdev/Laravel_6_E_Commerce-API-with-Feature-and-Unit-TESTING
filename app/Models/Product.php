<?php

namespace App\Models;

use App\Models\Traits\CanBeFiltered;
use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use CanBeFiltered, HasPrice;
    
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function in_stock()
    {
        return $this->stockCount() > 0;
    }

    public function stockCount()
    {
        return $this->variations->sum(function ($variation){
            return $variation->stockCount();
        });
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