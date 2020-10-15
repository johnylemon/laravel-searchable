# Filter CRUD resources using simple query parameters

![GitHub Workflow Status](https://img.shields.io/github/workflow/status/johnylemon/laravel-searchable/run-tests?label=tests)
![GitHub tag (latest by date)](https://img.shields.io/github/v/tag/johnylemon/laravel-searchable?label=release)

This library makes searching and filtering super easy.

## Getting started

 1. Add repository

```
composer require johnylemon/laravel-searchable
```
2. Register `Johnylemon\Searchable\Providers\SearchableServiceProvider`

3. Publish config using publish command

```
php artisan vendor:publish
```

4. add `Johnylemon\Searchable\Traits\Searchable` trait any model you want and `searchable` method defining searchable properties

```php

<?php

use Johnylemon\Searchable\Traits\Searchable;

class User extends Model
{
    use Searchable;

    /**
     * Searchable properties array
     *
     * @return    array    searchables
     */
    protected function searchable(): array
    {
        return [
            'name',
            'email',
        ];
    }
}

```

5. **Enjoy!**


## Basic usage

To enable searching for current query use `withSearch` scope:

```php
ModelName::withSearch()->get();
```

Example presented below will return all users named `John`

```php

Route::get('/users?name=John', function(){

    return ModelName::withSearch()->get();

});


```

This will return users with `name` set to `John` and `nick` set to `johnylemon`

```php

Route::get('/users?name=John&nick=johnylemon', function(){

    return ModelName::withSearch()->get();

});



```

## Default search filter

By default every property will be filtered using `BasicSearch` search filter.
This filter will use simple `where("property", "value")` condition.

Feel free to change this setting in you config file.


## Customizing searchables and aliases

Searchable array may return more complex array than simple property names.

You may define which filter should be used for every field:

```php

use App\Search\CustomSearch;

/**
 * Searchable properties array
 *
 * @return    array    searchables
 */
public function searchable(): array
{

    return [
        // will be searched using basic seach
        'last_name',

        // will use `CustomSearch` filter for `first_name` field
        'first_name' => CustomSearch::class,

        // instead of using custom class, you may use anonymus function
        'full_name' => function($query, $property, $value) {
            $query->where(DB::raw("first_name || ' ' || last_name"), $value);
        },

        // `name` field will be searched same way as `first_name`
        'name' => 'first_name',
    ];

}

```

Note that `name` column may be real column in database or not.
This may be handy if you would like to hide real column names in your search query.

This example will search for `ghost` users, thanks to aliases:

```php

public function searchable(): array
{

    return [
        'ghost' => function($query, $property, $value) {
            $query->whereNotNull('deleted_at');
        },
    ];

}

```


## Build-in aliases

This package ships with three handy search filters, that may be used for common searching:


#### `Like` search filter
Uses `Johnylemon\Searchable\Search\LikeSeach` class. Will add `%LIKE%` condition.

#### `LikeBegin` search filter
Uses `Johnylemon\Searchable\Search\LikeBeginSeach` class. Will add `LIKE%` condition.

#### `LikeEnd` search filter
Uses `Johnylemon\Searchable\Search\LikeEndSeach` class. Will add `%LIKE` condition.

Feel free to use them for your searchables.

Of course typing entire class name each time may be cumbersome, so this package allows you to define custom, easy to remember and type aliases within your config file.

Build-in search filters also can be used as `like`, `like-begin`, and `like-end` shorthands.


```php

public function searchable(): array
{

    return [
        //  will be searched using `%LIKE%` provided by `LikeSearch` class
        'email' => 'like',

        //  will be searched using `LIKE%` provided by `LikeBeginSearch` class
        'code' => 'like-begin',

        //  will be searched using `%LIKE` provided by `LikeEndSearch` class
        'suffix' => 'like-end',
    ];

}

```


## Commands

This package ships with `searchable:generate` command, which can be used to rapid generating custom filter classes.

This command will place brand new class in directory specified in config
```
php artisan searchable:generate MySearch
```

## Testing
You can run the tests with:

```
vendor/bin/phpunit
```

## License
The MIT License (MIT)


## Contact

Visit me at [https://johnylemon.dev](https://johnylemon.dev)

---

Developed with ❤ by [johnylemon](https://github.com/johnylemon).
