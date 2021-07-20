<?php

namespace Ibis117\Craft\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Ibis117\Craft\Commands\ReplaceCommand;

class ControllerCommand extends Command
{

    use CommandHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'craft:controller {model}';

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
    protected $namespace = "App\Http\Controllers";
    protected $model_namespace = "App\Models";

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
        $model = ucfirst($this->argument('model'));
        $folder = config('craft.controller.folder');
        $model_folder = config('craft.model.folder');
        $namespace = empty($folder) ? $this->namespace : join('\\', [$this->namespace, $folder]);
        $model_namespace = empty($model_folder) ? join('\\', [$this->model_namespace, $model]) : join('\\', [$this->model_namespace, $model_folder, $model]);
        $singular = Str::singular(strtolower($model));
        $plural = Str::plural(strtolower($model));
        $class = ucfirst($singular).'Controller';
        $stub = $this->getStub();
        $path = $this->getPath($class);
        if (!$this->files->exists($path)) {
            $this->makeDirectory($path);
        }
        $stub = $this->getStub();
        $this->replaceNamespace($stub, $namespace)
        ->replaceModelNamespace($stub, $model_namespace)
        ->replaceClassName($stub, $class)
        ->replaceModel($stub, $model)
        ->replaceSingular($stub, $singular)
        ->replacePlural($stub, $plural);

        $this->files->put($path, $stub);
        $this->info("Controller generated at {$path}");
        return 0;
    }


    private function getStub()
    {
        $path = __DIR__ . '/../../stubs/controller.stub';
        return $this->files->get($path);
    }

    private function getPath($class) {
        $folder = config('craft.controller.folder');
        $structure = array_filter(['app/Http/Controllers', $folder, $class.'.php'], function($value) {
            return $value != '';
        });
        $path = join(DIRECTORY_SEPARATOR, $structure);
        return base_path($path);
    }

}
