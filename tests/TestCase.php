<?php

namespace Johnylemon\Searchable\Tests;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Johnylemon\Searchable\Tests\Models\Test;

abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->getEnvironmentSetUp($this->app);
        $this->prepareDatabase();

        config()->set('laravel-searchable', require __DIR__.'/../config/laravel-searchable.php');
        require __DIR__.'/routes/test.php';
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function prepareDatabase()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
           $table->increments('id');
           $table->string('first_name');
           $table->string('last_name');
           $table->string('nick');
           $table->softDeletes();
       });

       Test::create([
           'first_name' => 'Janko',
           'last_name' => 'Walski',
           'nick' => 'JankoN',
       ]);

       Test::create([
           'first_name' => 'John',
           'last_name' => 'Doe',
           'nick' => 'JohnnyD',
       ]);

       Test::create([
           'first_name' => 'Jane',
           'last_name' => 'Doe',
           'nick' => 'JanneD',
       ]);
    }

    protected function getPackageProviders($app)
    {
        return ['Johnylemon\\Searchable\\Providers\SearchableServiceProvider'];
    }
}
