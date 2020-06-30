<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Str;

class CreateRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository
                            {name : Pass repository name}
                            {--model= : Pass value models (optioanl)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate repository commnad';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $model = $this->option('model');
        if (!$model) {
            $model = 'App\\Models\\Example';
        } else {
            $model = 'App\\Models\\'.$model;
        }
        $repositoryTemplate = str_replace([
            '{{repositoryName}}',
            '{{model}}'
        ],
        [
            $name,
            $model
        ],
        $this->getStub('Repository'));
        file_put_contents(app_path("/Repositories/{$name}.php"), $repositoryTemplate);
        $this->info("Repository $name is created");
    }

    protected function getStub($type){
        return file_get_contents(resource_path("stubs/$type.stub"));
    }
}
