<?php

use Johnylemon\Searchable\Tests\Models\Test;
use Johnylemon\Searchable\Tests\Models\TestLike;

Route::get('no-searchable', function () {
    return response()->json(Test::all());
});

Route::get('searchable-test', function () {
    return response()->json(Test::withSearch()->get());
});

Route::get('searchable-test-like', function () {
    return response()->json(TestLike::withSearch()->get());
});
