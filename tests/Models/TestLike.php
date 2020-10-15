<?php

namespace Johnylemon\Searchable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Johnylemon\Searchable\Traits\Searchable;
use DB;

class TestLike extends Test
{
    protected function searchable(): array
    {
        return [
            'first_name' => 'like-begin',
            'last_name' => 'like-end',
            'nick' => 'like',
        ];
    }
}
