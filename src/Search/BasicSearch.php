<?php

namespace Johnylemon\Searchable\Search;

class BasicSearch extends Search
{
    /**
     * @inheritDoc
     */
    public function apply($query, string $property, $value): void
    {
        $query->where($property, $value);
    }
}
