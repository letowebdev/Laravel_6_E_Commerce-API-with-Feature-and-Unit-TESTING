<?php

namespace App\Models;

use App\Models\Traits\IsOrderable;
use App\Models\Traits\IsParent;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use IsParent, IsOrderable;

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }




}
