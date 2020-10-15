<?php

namespace Johnylemon\Searchable\Search;

abstract class Search
{
    /**
     * Apply filtering logic
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $property    property name
     * @param  mixed   $value       property value
     */
    abstract function apply($query, string $property, $value): void;
}
