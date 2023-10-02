<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Create a new repository';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $stubInterface = file_get_contents(app_path('Stubs/ModelRepositoryInterface.stub'));
        $stubInterface = str_replace('Model', $name, $stubInterface);

        $stub = file_get_contents(app_path('Stubs/ModelRepository.stub'));
        $stub = str_replace('Model', $name, $stub);

        file_put_contents(app_path("Interfaces/Repositories/{$name}RepositoryInterface.php"), $stubInterface);
        $this->info("[Interfaces/Repositories/{$name}RepositoryInterface.php] repository interface created successfully");

        file_put_contents(app_path("Repositories/{$name}Repository.php"), $stub);
        $this->info("[Repositories/{$name}Repository.php] repository created successfully");
    }
}
