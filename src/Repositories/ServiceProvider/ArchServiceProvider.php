<?php

namespace Arch\Repositories\ServiceProvider;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    private $commandPath = 'command.bpocallaghan.';
    private $packagePath = 'Bpocallaghan\Generators\Commands\\';
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
        // merge config
        $configPath = __DIR__ . '/config/config.php';
        $this->mergeConfigFrom($configPath, 'generators');
        // register all the artisan commands
        $this->registerCommand('Repositories', 'publish');
        
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