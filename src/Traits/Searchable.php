<?php

namespace Johnylemon\Searchable\Traits;

trait Searchable
{
    use PreparesSearchable;

    /**
     * Searchable properties array
     *
     * @return    array    searchables
     */
    protected function searchable(): array
    {
        return [];
    }

    /**
     * Search enabling scope
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithSearch($query)
    {
        return $this->buildSearchQuery($query);
    }
}
