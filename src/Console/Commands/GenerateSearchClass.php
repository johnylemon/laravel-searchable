<?php

namespace Johnylemon\Searchable\Console\Commands;

use Illuminate\Console\Command;

class GenerateSearchClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'searchable:generate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates search class';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //
        // classname
        //
        $class = $this->argument('name');

        //
        // target location
        //
        $dir = config('laravel-searchable.dir');

        //
        // target path
        //
        $path = app_path("$dir/$class.php");

        stub($this->stub(), $path, [
            'NAMESPACE' => $this->namespace($dir),
            'NAME' => $class,
        ]);

        return 0;
    }

    /**
     * Prepare namespace for stub class file
     *
     * @param     string    $path    stub directory
     * @return    string             generated namespace
     */
    protected function namespace(string $path): string
    {
        return 'App\\'.str_replace(['/'], ['\\'], $path);
    }

    /**
     * Returns stub file path
     *
     * @return    string    stub path
     */
    protected function stub(): string
    {
        return __DIR__.'/../../../stubs/search.stub';
    }
}
