<?php

namespace App\Filtering;

use App\Filtering\Contracts\TheFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Filter
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function apply(Builder $builder, array $filters)
    {
        foreach ($this->limitFilters($filters) as $key => $filter) {
            if (!$filter instanceof TheFilter) {
                continue;
            }
            $filter->apply($builder, $this->request->get($key));
        }
        return $builder;
    }

    protected function limitFilters(array $filters)
    {
        return Arr::only(
            $filters,
            array_keys($this->request->all())
        );
    }






}