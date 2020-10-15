<?php

namespace Johnylemon\Searchable\Providers;

use Illuminate\Support\ServiceProvider;
use Johnylemon\Searchable\Console\Commands\GenerateSearchClass;

class SearchableServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->publishes([
            __DIR__.'/../../config/laravel-searchable.php' => config_path('laravel-searchable.php'),
        ], 'laravel-searchable');

        if ($this->app->runningInConsole())
        {
            $this->commands([
                GenerateSearchClass::class,
            ]);
        }
    }
}
