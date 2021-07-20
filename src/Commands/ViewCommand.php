<?php

namespace Ibis117\Craft\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Ibis117\Craft\Commands\ReplaceCommand;

class ViewCommand extends Command
{

    use CommandHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'craft:view {table}';

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
    protected $blades = [
        'index', 'show', 'create', 'edit'
    ];

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

        $name = strtolower(Str::singular($table));

        $extends = config('craft.view.extends');
        foreach ($this->blades as $value) {
            $class = join(DIRECTORY_SEPARATOR, [$name, $value]);
            $path = $this->getPath($class);
            if (!$this->files->exists($path)) {
                $this->makeDirectory($path);
            }
            $stub = $this->getStub();
            $this->replaceExtendInView($stub, $extends);
            $this->files->put($path, $stub);
            $this->info("View generated at {$path}");
        }
        return 0;
    }

    protected function replaceExtendInView(&$stub, $with)
    {
        $for = '{{ extends }}';
        $stub = $this->replace($stub, $for, $with);
        return $this;
    }

    private function getStub()
    {
        $path = $this->files->exists(base_path('stubs/view.stub')) ? base_path('stubs/view.stub') : __DIR__ . '/../../stubs/view.stub';
        return $this->files->get($path);
    }

    private function getPath($class) {
        $folder = config('craft.controller.folder');
        $structure = array_filter([resource_path('views'), $folder, $class.'.blade.php'], function($value) {
            return $value != '';
        });
        $path = join(DIRECTORY_SEPARATOR, $structure);
        return $path;
    }

}
