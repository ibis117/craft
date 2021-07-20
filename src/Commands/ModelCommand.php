<?php

namespace Ibis117\Craft\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Ibis117\Craft\Commands\ReplaceCommand;

class ModelCommand extends Command
{

    use CommandHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'craft:model {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to generate controller';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $files;
    protected $namespace = "App\Models";

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $table = ucfirst($this->argument('table'));
        $folder = config('craft.model.folder');
        $namespace = empty($folder) ? $this->namespace : join('\\', [$this->namespace, $folder]);
        $singular = Str::singular(strtolower($table));
        $class = ucfirst($singular);
        $stub = $this->getStub();
        $path = $this->getPath($class);
        if (!$this->files->exists($path)) {
            $this->makeDirectory($path);
        }
        $stub = $this->getStub();
        $this->replaceNamespace($stub, $namespace)
        ->replaceClassName($stub, $class);
        $this->files->put($path, $stub);
        $this->info("Model generated at {$path}");
        return 0;
    }


    private function getStub()
    {
        $path = __DIR__ . '/../../stubs/model.stub';
        return $this->files->get($path);
    }

    private function getPath($class) {
        $folder = config('craft.controller.folder');
        $structure = array_filter(['app/Models', $folder, $class.'.php'], function($value) {
            return $value != '';
        });
        $path = join(DIRECTORY_SEPARATOR, $structure);
        return base_path($path);
    }

}
