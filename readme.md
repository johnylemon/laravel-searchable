# Filter CRUD resources using simple query parameters

![GitHub Workflow Status](https://img.shields.io/github/workflow/status/johnylemon/laravel-searchable/run-tests?label=tests)
![GitHub tag (latest by date)](https://img.shields.io/github/v/tag/johnylemon/laravel-searchable?label=release)

This library makes searching and filtering super easy.
Simplicity is a magic.

## Getting started

 1. Add repository

```
composer require johnylemon/laravel-searchable
```

2. Register `Johnylemon\Searchable\Providers\SearchableServiceProvider` provider if not registered automagically .

3. Publish config using publish command

```
php artisan vendor:publish
```

4. Add `Johnylemon\Searchable\Traits\Searchable` trait any model you want and `searchable` method defining searchable properties

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

This will return users with `name` set to _John_ and `nick` set to _johnylemon_

```
GET /users?name=John&nick=johnylemon

```

Sometimes you may want to pass many possible values. To achieve that use array of values for given property.

This example will return all users with `name` _johny_ OR _lemon_, and `nick` set to _johnylemon_.
Every possible `name` value will use filter defined for `name` property.


```
GET /users?name[]=john&name[]=lemon&nick=johnylemon

```

Of course you may pass arrays fo more than one property.

This example will return all users with `name` _johny_ OR _lemon_, and `nick` set to _johnylemon_ OR _laravel_.


```
GET /users?name[]=john&name[]=lemon&nick[]=johnylemon&nick[]=laravel
```

## Default search filter

By default every property will be filtered using `Johnylemon\Searchable\Search\BasicSearch` search filter.
This filter will use simple `where("property", "value")` condition.
Feel free to change this setting in your config file.


This package ships with some handy search filters, that may be used for common searching:

#### `Like` search filter
Uses `Johnylemon\Searchable\Search\LikeSeach` class. Will add `%LIKE%` condition.

#### `LikeBegin` search filter
Uses `Johnylemon\Searchable\Search\LikeBeginSeach` class. Will add `LIKE%` condition.

#### `LikeEnd` search filter
Uses `Johnylemon\Searchable\Search\LikeEndSeach` class. Will add `%LIKE` condition.

Feel free to use them for your searchables.


## Customizing searchables

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

        // this field will be searched using `BasicSearch` class
        'last_name',

        // will use `CustomSearch` filter for `first_name` field
        'first_name' => CustomSearch::class,

        // instead of using custom class, you may use anonymous function
        'full_name' => function($query, $property, $value) {
            $query->where(DB::raw("first_name || ' ' || last_name"), $value);
        },

        // `name` field will be searched same way as `first_name`
        // so it will use `CustomSearch` class
        'name' => 'first_name',
    ];

}

```
Note that `full_name` field does not exist in the database.

## Aliases

Sometimes it may be handy to hide real column names in your search query.
This is where aliases comes to play. Example presented above used two fields that does not exist in the database: `full_name` and `name`.

The `full_name` property uses anonymous function that will use simple concatenation for searching for given value.

The `name` property points to `first_name` property, so it will be treated as `first_name` field. Of course `first_name` must be searchable.

If you would like to use `name` field to be used as `first_name` field BUT WITHOUT making `first_name` searchable, just use custom Search class or anonymous function:

```php

use App\Search\SearchAsFirstName;
use App\Search\SearchAsAnotherField;

public function searchable(): array
{
    return [
        //
        // use callable
        //
        'name' => function($query, $property, $value) {
            $query->where('first_name', $value);
        },

        //
        // or custom class
        //
        'name' => SearchAsFirstName::class,

        //
        // or even search class instance
        //
        'name' => new SearchAsAnotherField('first_name'),
    ];
}

```


And this simple example will search for `ghost` users, thanks to aliases:

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

## Shorthands

Of course typing entire class name or callables each time may be cumbersome, so this package allows you to define custom, easy to type and remember shorthands within your config file.

```php

'shorthands' => [
    'ghost' => App\Search\GhostUsers::class,
    'awesomness' => App\Search\AwesomeSearchFilter::class,
],

```

Shorthands takes precedence over field names.
Lets assume you have `name` field, and `username` field that points to `name` field, and there is also `name` shorthand. In that case shorthand will be used instead of `name` field.

```php

//
// config file
//
`shorthands` => [
    'name' => App\Search\NameSearch::class,
],

//
// model searchable method
//
public function searchable(): array
{
    return [
        'name',
        'username' => 'name', // `name` will be treated as shorthand, so `App\Search\NameSearch` search filter will be used  
    ];
}
```

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

This command will place brand new class in directory specified in config file.

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
