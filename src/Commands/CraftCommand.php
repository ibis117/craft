<?php

namespace Ibis117\Craft\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CraftCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'craft {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to generate model view controller';

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

        $model = Str::singular($table);
        $this->call('craft:model', ['table' => $table]);
        $this->call('craft:view', ['table' => $table]);
        $this->call('craft:controller', ['model' => $model]);
    }
}
