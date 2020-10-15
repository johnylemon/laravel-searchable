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
    }
}
