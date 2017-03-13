<?php

namespace Arch\Repositories\Commands;

use Illuminate\Console\GeneratorCommand as Command;

class Repositories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shareable:repositories {repoName} {--model=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating repositories for shareable library';    
    // model Name
    private $modelName = null;
    // app path
    private $appPath = null;
    // base path
    private $basePath = null;
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        // get argument, name of repositories
        $repoName = $this->argument( 'repoName' );
        // assign app path
        $this->appPath = app_path();
        // get repositories path
        $repoPath = $this->appPath . '/Repositories';
        // get option for model name
        $this->modelName = $this->option( 'model' );
        // get repositories file path
        $file = $repoPath . '/' . $repoName . '.php';
        // checking if file already exist or not
        if ( file_exists( $file ) ) {            

            $this->error( 'File with ' . $repoName . ' already existed' );
            exit;

        }
        // check if folder already exist or not
        if ( !file_exists( $repoPath ) ) {
            mkdir( $repoPath, 0777, true );
        }
        // get base path
        $this->basePath = base_path();

        $kk = $this->buildClass( ucfirst($repoName) );
        // make all the directories
        $this->makeDirectory( $file );
        // build file and save it at location
        $this->files->put( $file, $kk );
        // output to console
        $this->info( ucfirst( $repoName . ' created successfully.' ) );
        // if we need to run "composer dump-autoload"
       /* if ($this->settings['dump_autoload'] === true) {
            $this->composer->dumpAutoloads();
        }*/
    }

    public function getStub() {
        return $this->basePath.'/vendor/arch/repositories/src/Repositories/Stub/repositories.stub';
    }

    protected function buildClass($name) {
        $stub = $this->files->get($this->getStub());
        return $this->replaceModelName( $stub )->replaceClass($stub, $name);
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name) {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace('{{DummyClass}}', $class, $stub);
    }

    protected function replaceModelName( &$stub) {       
        
        // replace model name
        $stub = str_replace('{{modelName}}', ucfirst( $this->modelName ), $stub);
        // check if model name provided using fully qualified name or not
        if ( preg_match( "/\\\\/i", $this->modelName ) ) {

            $modelExplode = explode( '\\', $this->modelName );
            $modelInstance = ucfirst( $modelExplode[ count($modelExplode) - 1 ] );

        }
        else {
            $modelInstance = $this->modelName;
        }

        $stub = str_replace('{{modelNameInstance}}', ucfirst( $modelInstance ), $stub);

        return $this;
    }

}
