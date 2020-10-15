<?php

namespace Johnylemon\Searchable\Search;

class LikeSearch extends Search
{
    protected function like(string $value): string
    {
        return "%$value%";
    }

    public function apply($query, $property, $value)
    {
        return $query->where($property, 'LIKE', $this->like($value));
    }
}
