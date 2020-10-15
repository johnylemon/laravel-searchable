<?php

namespace Johnylemon\Searchable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Johnylemon\Searchable\Traits\Searchable;
use DB;

class Test extends Model
{
    use Searchable;

    protected $table = 'test_models';
    public $timestamps = FALSE;

    protected $fillable = [
        'first_name',
        'last_name',
        'nick',
    ];

    protected function searchable(): array
    {
        return [
            'first_name',
            'last_name',
            'surname' => 'last_name',
            'name' => 'full_name',
            'full_name' => function($query, $property, $value) {
                $query->where(DB::raw("first_name || ' ' || last_name"), $value);
            },
        ];
    }
}
