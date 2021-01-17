<?php
namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait IsOrderable
{
    public function scopeOrdered(Builder $builder, $sorted = 'asc')
    {
        $builder->orderBy('order', $sorted);
    }
}