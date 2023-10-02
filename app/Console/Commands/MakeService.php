<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $stubInterface = file_get_contents(app_path('Stubs/ModelServiceInterface.stub'));
        $stubInterface = str_replace('Model', $name, $stubInterface);

        $stub = file_get_contents(app_path('Stubs/ModelService.stub'));
        $stub = str_replace('Model', $name, $stub);

        file_put_contents(app_path("Interfaces/Services/{$name}ServiceInterface.php"), $stubInterface);
        $this->info("[Interfaces/Services/{$name}ServiceInterface.php] service interface created successfully");

        file_put_contents(app_path("Services/{$name}Service.php"), $stub);
        $this->info("[Services/{$name}Service.php] service created successfully");
    }
}
