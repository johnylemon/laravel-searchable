<?php

namespace Johnylemon\Searchable\Search;

use Closure;

class ClosureSearch extends Search
{
    /**
     * Closure to be called later
     * @var    \Closure
     */
    protected $closure;

    
    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * @inheritDoc
     */
    public function apply($query, string $property, $value): void
    {
        $closure = $this->closure;

        $closure(...func_get_args());
    }
}
