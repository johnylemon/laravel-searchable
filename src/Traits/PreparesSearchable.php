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

    protected function applyFilter($query, $property, $value)
    {
        $filter = $this->findFilter($property, $this->searchable());

        if(!$filter)
            return $query;

        return $filter->apply($query, $property, $value);
    }

    protected function findFilter(&$property, array $searchables = []): ?Search
    {
        if(isset($searchables[$property]))
        {
            $filter = $searchables[$property];

            if(is_string($filter))
            {
                if($this->isAlias($filter))
                {
                    return app($this->getAlias($filter));
                }

                if(class_exists($filter))
                    return app($filter);
            }

            if($filter instanceof Search)
                return $filter;

            if($filter instanceof Closure)
                return app()->makeWith(ClosureSearch::class, ['closure' => $filter]);

            $property = $filter;

            return $this->findFilter($property, $searchables);
        }

        if(in_array($property, $searchables))
            return app(config('laravel-searchable.default_search'));

        return NULL;
    }

    protected function isAlias($filter)
    {
        return isset(config('laravel-searchable.aliases')[$filter]);
    }

    protected function getAlias($filter)
    {
        return config('laravel-searchable.aliases')[$filter];
    }
}
