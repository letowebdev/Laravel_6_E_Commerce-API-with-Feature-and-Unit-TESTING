<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Filtering\Filter;

trait CanBeFiltered
{
    public function scopeWithFilter(Builder $builder, $filters = [])
    {
        return (new Filter (request()))->apply($builder, $filters);
    }
}