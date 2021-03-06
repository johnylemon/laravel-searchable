<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Search
    |--------------------------------------------------------------------------
    |
    | This value defines class name of the class that will be used as default query builder for searchable parameters.
    | \Johnylemon\Searchable\Search\BasicSearch class will add basic where equation claim.
    |
    */

    'default_search' => Johnylemon\Searchable\Search\BasicSearch::class,


    /*
    |--------------------------------------------------------------------------
    | Search classes directory
    |--------------------------------------------------------------------------
    |
    | This value decides where generated Search classeswill be placed,
    | relative to the `app` directory
    |
    */

    'dir' => 'Search',


    /*
    |--------------------------------------------------------------------------
    | Search class shorthands
    |--------------------------------------------------------------------------
    |
    | Here you can define any search class shorthand you want.
    |
    | Key will be the name of your shorthand and the value will be target class name
    |
    */

    'shorthands' => [
        'like' => Johnylemon\Searchable\Search\LikeSearch::class,
        'like-end' => Johnylemon\Searchable\Search\LikeEndSearch::class,
        'like-begin' => Johnylemon\Searchable\Search\LikeBeginSearch::class,
    ],

];
