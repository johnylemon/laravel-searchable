<?php

namespace Johnylemon\Searchable\Search;

class LikeEndSearch extends LikeSearch
{
    /**
     * @inheritDoc
     */
    protected function like(string $value): string
    {
        return "%$value";
    }
}
