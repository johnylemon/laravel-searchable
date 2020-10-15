<?php

namespace Johnylemon\Searchable\Search;

class LikeBeginSearch extends LikeSearch
{
    protected function like(string $value): string
    {
        return "$value%";
    }
}
