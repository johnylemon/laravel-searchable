<?php

namespace Johnylemon\Searchable\Providers;

use Illuminate\Support\ServiceProvider;
use Johnylemon\Searchable\Console\Commands\GenerateSearchClass;

class SearchableServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        // publish config
        //
        $this->publishes([
            __DIR__.'/../../config/laravel-searchable.php' => config_path('laravel-searchable.php'),
        ], 'laravel-searchable');

        //
        // load command if in console
        //
        if ($this->app->runningInConsole())
        {
            $this->commands([
                GenerateSearchClass::class,
            ]);
        }

        //
        // fix config
        // 
        $this->fixDeprecatedConfig();
    }

    /**
     * Fix deprecated config
     *
     */
    protected function fixDeprecatedConfig()
    {
        //
        // previous package config contained `aliases` key
        // but this key is renamed to `shorthands`.
        //
        // So to prevent breaking changes
        // we will simply merge old `aliases` array with new `shorthands` array
        //
        //
        $old = config('laravel-searchable.aliases', []);
        $new = config('laravel-searchable.shorthands', []);

        config([
            'laravel-searchable.shorthands' => array_merge($old, $new)
        ]);
    }
}
