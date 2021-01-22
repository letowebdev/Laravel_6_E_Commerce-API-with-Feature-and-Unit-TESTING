<?php

namespace App\Filtering\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface TheFilter
{
    public function apply(Builder $builder, $value);
}