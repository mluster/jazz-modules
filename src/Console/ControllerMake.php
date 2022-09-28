<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class ControllerMake extends ControllerMakeCommand
{
    use TGenerator {
        buildClass as myBuildClass;
    }

    protected function buildClass($name): string
    {
        $stub = $this->myBuildClass($name);

        if ($this->option('parent')) {
            $parent = $this->qualifyModel($this->option('parent'));
            $stub = $this->replaceParentModels($stub, $this->confirmModel($parent));
        }
        if ($this->option('model')) {
            $model = $this->qualifyModel($this->option('model'));
            $stub = $this->replaceModels($stub, $this->confirmModel($model));
            $stub = $this->replaceRequests($stub, $model);
        }

        $controllerNamespace = $this->getNamespace($name);
        return str_replace('use ' . $controllerNamespace . "\Controller;\n", '', $stub);
    }

    protected function confirmModel(string $model): string
    {
        if (!class_exists($model)) {
            // $question = class_basename($model) . ' Model does not exist. Do you want to generate it?';
            $question = 'Model does not exist. Do you want to generate it?';
            if ($this->confirm($question)) {
                $modelName = str_replace('\\', '/', Str::after($model, 'Models\\'));
                $moduleKey = Config::get('modules.key');

                $args = [
                    'name' => $modelName,
                    '--' . $moduleKey => $this->option($moduleKey),
                ];
                $this->call('make:model', $args);
            }
        }

        return $model;
    }

    protected function getStub(): string
    {
        $stub = $this->getStubNonApi();
        if ($this->option('api')) {
            if ($stub !== null && !$this->option('invokable')) {
                $stub = str_replace('.stub', '.api.stub', $stub);
            }
            if ($stub === null) {
                $stub = 'controller.api.stub';
            }
        }
        $stub = ($stub ?? 'controller.plain.stub');

        return $this->getStubFile($stub);
    }

    private function getStubNonApi(): ?string
    {
        $stub = null;

        if ($this->option('parent')) {
            $stub = 'controller.nested.stub';
        } elseif ($this->option('model')) {
            $stub = 'controller.model.stub';
        } elseif ($this->option('invokable')) {
            $stub = 'controller.invokable.stub';
        } elseif ($this->option('resource')) {
            $stub = 'controller.stub';
        }

        return $stub;
    }



    protected function replaceRequests(string $stub, string $model): string
    {
        $namespace = 'Illuminate\\Http';

        $storeRequest = $updateRequest = 'Request';
        if ($this->option('requests')) {
            $namespace = $this->rootNamespace() . 'Http\\Requests';
            [$storeRequest, $updateRequest] = $this->generateFormRequests(
                $model,
                $storeRequest,
                $updateRequest
            );
        }

        $namespacedRequests = $namespace . '\\' . $storeRequest . ';';
        if ($storeRequest !== $updateRequest) {
            $namespacedRequests .= PHP_EOL . 'use ' . $namespace . '\\' . $updateRequest . ';';
        }

        $stub = str_replace(['{{storeRequest}}', '{{ storeRequest }}'], $storeRequest, $stub);
        $stub = str_replace(['{{namespacedStoreRequest}}', '{{ namespacedStoreRequest }}'], $namespace . '\\' . $storeRequest, $stub);
        $stub = str_replace(['{{updateRequest}}', '{{ updateRequest }}'], $updateRequest, $stub);
        $stub = str_replace(['{{namespacedUpdateRequest}}', '{{ namespacedUpdateRequest }}'], $namespace . '\\' . $updateRequest, $stub);
        $stub = str_replace(['{{namespacedRequests}}', '{{ namespacedRequests }}'], $namespacedRequests, $stub);
        return $stub;
    }

    protected function generateFormRequests($modelClass, $storeRequestClass, $updateRequestClass): array
    {
        $moduleKey = Config::get('modules.key');

        $storeRequestClass = 'Store' . class_basename($modelClass) . 'Request';
        $this->call('make:request', [
            'name' => $storeRequestClass,
            '--' . $moduleKey => $this->option($moduleKey),
        ]);

        $updateRequestClass = 'Update' . class_basename($modelClass) . 'Request';
        $this->call('make:request', [
            'name' => $updateRequestClass,
            '--' . $moduleKey => $this->option($moduleKey),
        ]);

        return [$storeRequestClass, $updateRequestClass];
    }
}
