<?php

namespace App\Filtering\Filters;

use App\Filtering\Contracts\TheFilter;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter implements TheFilter
{
    public function apply(Builder $builder, $value)
    {
        return $builder->whereHas('categories', function ($builder) use ($value){
            $builder->where('slug', $value);
        });
    }
}