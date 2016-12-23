<?php

namespace Arch\Repositories\Commands;

use Illuminate\Console\GeneratorCommand as Command;

class Canal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shareable:canal {canalName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating canal central reusable code for shareable library';
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
        $canalName = $this->argument( 'canalName' );
        // assign app path
        $this->appPath = app_path();
        // get repositories path
        $canalPath = $this->appPath . '\Canal';
        // get repositories file path
        $file = $canalPath . '\\' . $canalName . '.php';
        // checking if file already exist or not
        if ( file_exists( $file ) ) {            

            $this->error( 'File with ' . $canalName . ' already existed' );
            exit;

        }
        // check if folder already exist or not
        if ( !file_exists( $canalPath ) ) {
            mkdir( $canalPath, 0777, true );
        }
        // get base path
        $this->basePath = base_path();

        $kk = $this->buildClass( ucfirst($canalName) );
        // make all the directories
        $this->makeDirectory( $file );
        // build file and save it at location
        $this->files->put( $file, $kk );
        // output to console
        $this->info( ucfirst( $canalName . ' created successfully.' ) );
        // if we need to run "composer dump-autoload"
       /* if ($this->settings['dump_autoload'] === true) {
            $this->composer->dumpAutoloads();
        }*/
    }

    public function getStub() {
        return $this->basePath.'\vendor\arch\repositories\src\Repositories\Stub\canal.stub';
    }

    protected function buildClass($name) {
        $stub = $this->files->get($this->getStub());
        return $this->replaceClass($stub, $name);
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
    

}
