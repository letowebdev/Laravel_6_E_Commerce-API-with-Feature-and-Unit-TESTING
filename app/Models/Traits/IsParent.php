<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait IsParent
{
    public function scopeParents(Builder $builder)
    {
        $builder->whereNull('parent_id');
    }
}