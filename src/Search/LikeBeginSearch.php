<?php

namespace Johnylemon\Searchable\Search;

class LikeBeginSearch extends LikeSearch
{
    /**
     * @inheritDoc
     */
    protected function like(string $value): string
    {
        return "$value%";
    }
}
