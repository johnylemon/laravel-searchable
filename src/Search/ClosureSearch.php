<?php

namespace Johnylemon\Searchable\Search;

use Closure;

class ClosureSearch extends Search
{
    protected $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function apply($query, $property, $value)
    {
        $closure = $this->closure;

        return $closure(...func_get_args());
    }
}
