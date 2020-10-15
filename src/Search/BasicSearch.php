<?php

namespace Johnylemon\Searchable\Search;

class BasicSearch extends Search
{
    public function apply($query, $property, $value)
    {
        return $query->where($property, $value);
    }
}
