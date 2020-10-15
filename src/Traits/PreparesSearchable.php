<?php

namespace Johnylemon\Searchable\Traits;

use Closure;
use Exception;
use Johnylemon\Searchable\Search\{
    Search,
    BasicSearch,
    ClosureSearch
};

trait PreparesSearchable
{
    /**
     * Generate seach query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildSearchQuery($query)
    {
        foreach(request()->query() as $property => $value)
        {
            if(blank($value))
                continue;

            $this->applyFilter($query, $property, $value);
        }

        return $query;
    }

    /**
     * Applies
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyFilter($query, $property, $value)
    {
        $filter = $this->findFilter($property, $this->searchable());

        if(!$filter)
            return $query;

        return $filter->apply($query, $property, $value);
    }

    /**
     * Returns filter valid for current case
     *
     * @param     string    $property       property name
     * @param     array     $searchables    searchable properties
     * @return    null|Johnylemon\Searchable\Search\Seach   Search instance or null if not set
     */
    protected function findFilter(string &$property, array $searchables = []): ?Search
    {
        if(isset($searchables[$property]))
        {
            $filter = $searchables[$property];

            if(is_string($filter))
            {
                if($this->isAlias($filter))
                    return $this->buildFilter($this->getAlias($filter));

                if(class_exists($filter))
                    return $this->buildFilter($filter);
            }

            if($filter instanceof Search)
                return $filter;

            if($filter instanceof Closure)
                return $this->buildFilter(ClosureSearch::class, ['closure' => $filter]);

            $property = $filter;

            return $this->findFilter($property, $searchables);
        }

        if(in_array($property, $searchables))
            return app(config('laravel-searchable.default_search'));

        return NULL;
    }

    /**
     * Build filter
     *
     * @param     string    $filter    filter name
     * @param     array     $params    build parameter
     * @return    Johnylemon\Searchable\Search\Seach               Search filter
     */
    protected function buildFilter(string $filter, array $params = []): Search
    {
        return app()->makeWith($filter, $params);
    }

    /**
     * Check if passed filter name is an alias
     *
     * @param     string     $filter    filter name
     * @return    bool
     */
    protected function isAlias(string $filter): bool
    {
        return isset(config('laravel-searchable.aliases')[$filter]);
    }

    /**
     * Get concrete filter name for given alias
     *
     * @param     string    $alias    alias name
     * @return    string
     */
    protected function getAlias(string $alias): string
    {
        return config('laravel-searchable.aliases')[$alias];
    }
}
