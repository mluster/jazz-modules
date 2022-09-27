<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class FactoryMake extends FactoryMakeCommand
{
    use TGenerator {
        buildClass as myBuildClass;
    }

    protected function buildClass($name): string
    {
        $stub = $this->myBuildClass($name);

        $namespaceModel = $this->option('model')
            ? $this->qualifyModel($this->option('model'))
            : $this->qualifyModel($this->guessModelName($name));

        $stub = $this->replaceModels($stub, $namespaceModel);
        return $this->replaceFactoryNamespace($stub, $namespaceModel);
    }

    protected function guessModelName($name): string
    {
        if (Str::endsWith($name, 'Factory')) {
            $name = Str::substr($name, 0, -7);
        }

        $modelName = $this->qualifyModel($name);
        if (class_exists($modelName)) {
            return $modelName;
        }

        $name = trim(Str::replaceFirst($this->rootNamespace(), '', $name), '\\');
        return $this->rootNamespace() . '\\Models\\' . $name;
    }

    protected function getPath($name): string
    {
        $path = $this->laravel->basePath() . '/';

        ['name' => $module, 'meta' => $meta] = $this->getModule();
        if ($module) {
            $moduleNamespace = $meta['namespace'] . $module . '\\';

            $name = Str::replaceFirst($moduleNamespace, '', $name);
            $name = Str::finish($name, 'Factory');

            $path .= $meta['path'] . '/' . $module . '/' . $meta['assets'] . '/' . $meta['factories']['path'] . '/';
        } else {
            $name = Str::replaceFirst('App\\', '', $name);
            $name = Str::finish($name, 'Factory');

            $path .= 'database/factories/';
        }
        $path .= str_replace('\\', '/', $name) . '.php';

        return $path;
    }

    protected function getStub(): string
    {
        return $this->getStubFile('factory.stub');
    }

    protected function replaceFactoryNamespace(string $stub, string $model): string
    {
        $namespace = 'Database\\Factories\\';

        ['name' => $module, 'meta' => $meta] = $this->getModule();
        if ($module) {
            $namespace = $this->rootNamespace() . '\\' . $meta['factories']['namespace'];
        }

        $namespace .= Str::beforeLast(Str::after($model, '\\Models\\'), class_basename($model));
        $namespace = trim($namespace, '\\');

        $search = ['DummyFactoryNamespace', '{{factoryNamespace}}', '{{ factoryNamespace }}'];
        return str_replace($search, $namespace, $stub);
    }
}
