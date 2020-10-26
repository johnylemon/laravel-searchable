<?php

namespace Johnylemon\Searchable\Traits;

use Illuminate\Database\Eloquent\Builder;
use Closure;
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
    protected function buildSearchQuery($query): Builder
    {
        foreach(request()->query() as $property => $value)
        {
            if(blank($value))
                continue;

            //
            // if `$passedValue` is an array we assume that
            // user want to use `whereIn` query.
            // Since there is no `whereInLike` method (yet ;])
            // we have to use simple orWhere call with anonymous function
            //
            if(is_array($value))
            {
                $this->applyArrayFilter($query, $property, $value);
                continue;
            }

            $this->applyFilter($query, $property, $value);
        }

        return $query;
    }

    /**
     * Applies filter for each value of passed property
     * Handles wherein functionality
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $property  property name
     * @param  array  $passedValue  property value
     */
    protected function applyArrayFilter($query, $property, array $passedValue = [])
    {
        //
        // first we have to add `where` wrapper as usual
        //
        $query->where(function($query) use($property, $passedValue) {

            //
            // then add `orWhere` clause within this wrapper
            // to keep clauses scoped properly
            //
            foreach($passedValue as $value)
            {
                $query->orWhere(function($query) use($property, $value){
                    $this->applyFilter($query, $property, $value);
                });
            }
        });
    }

    /**
     * Applies filter for current query
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $property  property name
     * @param  mixed  $value  property value
     */
    protected function applyFilter($query, $property, $value)
    {
        $filter = $this->findFilter($property, $this->searchable());

        if($filter)
            $filter->apply($query, $property, $value);
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
                if($this->isShorthand($filter))
                    return $this->buildFilter($this->getShorthand($filter));

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
     * Check if passed filter name is an shorthands array
     *
     * @param     string     $filter    filter name
     * @return    bool
     */
    protected function isShorthand(string $filter): bool
    {
        return isset(config('laravel-searchable.shorthands')[$filter]);
    }

    /**
     * Get concrete filter name for given shorthand
     *
     * @param     string    $alias    alias name
     * @return    string
     */
    protected function getShorthand(string $alias): string
    {
        return config('laravel-searchable.shorthands')[$alias];
    }
}
