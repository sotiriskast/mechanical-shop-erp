<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MakeModuleMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-migration {name : The name of the migration}
                          {module : The name of the module}
                          {--create= : The table to be created}
                          {--table= : The table to be updated}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file for a specific module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $module = $this->argument('module');
        $table = $this->option('create') ?: $this->option('table');

        // Generate migration name with timestamp
        $timestamp = date('Y_m_d_His');
        $filename = $timestamp . '_' . Str::snake($name) . '.php';

        // Define paths
        $modulePath = base_path("src/Modules/{$module}");
        $migrationPath = "{$modulePath}/database/migrations";

        // Create directories if they don't exist
        if (!File::exists($migrationPath)) {
            File::makeDirectory($migrationPath, 0755, true);
        }

        // Get stub content
        $stub = File::get(__DIR__ . '/../stubs/migration.stub');

        // Replace stub placeholders
        $stub = str_replace(
            ['{{table}}'],
            [$table ?: 'table_name'],
            $stub
        );

        // Create migration file
        File::put("{$migrationPath}/{$filename}", $stub);

        $this->info("Migration [{$filename}] created successfully for module [{$module}]");
    }
}
