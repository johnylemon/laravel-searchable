<?php

namespace Johnylemon\Searchable\Search;

abstract class Search
{
    abstract function apply($query, $property, $value);
}
