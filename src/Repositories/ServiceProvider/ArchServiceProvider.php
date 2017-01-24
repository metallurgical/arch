<?php

namespace Arch\Repositories\ServiceProvider;
use Illuminate\Support\ServiceProvider;

class ArchServiceProvider extends ServiceProvider
{
    private $commandPath = 'command.arch.';
    private $packagePath = 'Arch\Repositories\Commands\\';
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {       
        
        // register all the artisan commands
        $this->registerCommand('Repositories', 'repo');        
        $this->registerCommand('Canal', 'canal');
        /*
         * Register the service provider for the dependency.
         */
        $this->app->register( 'Arch\Repositories\ServiceProvider\ArchServiceProvider' );
        /*
         * Create aliases for the dependency.
         */
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias( 'Fence', 'Arch\Repositories\Tools\Libraries\Encryption' );
        
    }
    /**
     * Register a singleton command
     *
     * @param $class
     * @param $command
     */
    private function registerCommand($class, $command)
    {
        $this->app->singleton($this->commandPath . $command, function ($app) use ($class) {
            return $app[$this->packagePath . $class];
        });
        $this->commands($this->commandPath . $command);
    }
}