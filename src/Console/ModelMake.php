<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class ModelMake extends ModelMakeCommand
{
    use TGenerator;

    public function call($command, array $arguments = []): int
    {
        $key = Config::get('modules.key');
        $module = $this->option($key);
        if ($module) {
            $arguments['--' . $key] = $module;
        }
        return parent::call($command, $arguments);
    }

    protected function getStub(): string
    {
        $stub = 'model.stub';
        if ($this->option('pivot')) {
            $stub = 'model.pivot.stub';
        }
        if ($this->option('morph-pivot')) {
            $stub = 'model.morph.pivot.stub';
        }
        return $this->getStubFile($stub);
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\\Models';
    }


    protected function createFactory()
    {
        $moduleKey = Config::get('modules.key');
        $factory = Str::studly($this->argument('name'));

        $this->call('make:factory', [
            'name' => $factory . 'Factory',
            '--model' => $this->qualifyClass($this->getNameInput()),
            '--' . $moduleKey => $this->option($moduleKey),
        ]);
    }

    protected function createMigration()
    {
        $moduleKey = Config::get('modules.key');
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));
        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
            '--' . $moduleKey => $this->option($moduleKey),
        ]);
    }

    protected function createSeeder()
    {
        $moduleKey = Config::get('modules.key');
        $seeder = Str::studly(class_basename($this->argument('name')));

        $this->call('make:seeder', [
            'name' => "{$seeder}Seeder",
            '--' . $moduleKey => $this->option($moduleKey),
        ]);
    }

    protected function createController()
    {
        $moduleKey = Config::get('modules.key');
        $controller = Str::studly(class_basename($this->argument('name')));
        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('make:controller', array_filter([
            'name' => "{$controller}Controller",
            '--model' => $this->option('resource') || $this->option('api') ? $modelName : null,
            '--api' => $this->option('api'),
            '--requests' => $this->option('requests') || $this->option('all'),
            '--' . $moduleKey => $this->option($moduleKey),
        ]));
    }

    protected function createPolicy()
    {
        $moduleKey = Config::get('modules.key');
        $policy = Str::studly(class_basename($this->argument('name')));

        $this->call('make:policy', [
            'name' => "{$policy}Policy",
            '--model' => $this->qualifyClass($this->getNameInput()),
            '--' . $moduleKey => $this->option($moduleKey),
        ]);
    }
}
