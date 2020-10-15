<?php

namespace Johnylemon\Searchable\Traits;

trait Searchable
{
    use PreparesSearchable;

    protected function searchable(): array
    {
        return [];
    }

    public function scopeWithSearch($query)
    {
        return $this->buildSearchQuery($query);
    }
}
