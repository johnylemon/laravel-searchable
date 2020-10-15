<?php

namespace Johnylemon\Searchable\Search;

class LikeEndSearch extends LikeSearch
{
    protected function like(string $value): string
    {
        return "%$value";
    }
}
