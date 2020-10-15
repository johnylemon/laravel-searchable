<?php

namespace Johnylemon\Searchable\Search;

class LikeSearch extends Search
{
    /**
     * Build LIKE condition value
     * @param     string    $value
     * @return    string              like condition
     */
    protected function like(string $value): string
    {
        return "%$value%";
    }

    /**
     * @inheritDoc
     */
    public function apply($query, string $property, $value): void
    {
        $query->where($property, 'LIKE', $this->like($value));
    }
}
