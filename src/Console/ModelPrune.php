<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Console\PruneCommand;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use InvalidArgumentException;

class ModelPrune extends PruneCommand
{
    use TOptions, TModuleContext;

    protected function models(): Collection
    {
        $models = $this->option('model');
        $except = $this->option('except');

        if (!empty($models) && !empty($except)) {
            throw new InvalidArgumentException('The --models and --except options cannot be combined.');
        }

        if (!empty($models)) {
            return collect($models)->filter(function ($model) {
                return class_exists($model);
            })->values();
        }

        $fs = new Filesystem();
        $mapping = [
            app_path() . '/Models' => 'App\\Models\\',
        ];
        if (Config::has('modules')) {
            $contexts = Config::get('contexts');
            foreach ($contexts as $context) {
                $contextPath = base_path($context['_meta']['path']);
                if (!$fs->isDirectory($contextPath)) {
                    continue;
                }
                foreach ($fs->directories($contextPath) as $directory) {
                    $modelsDir = $contextPath . '/' . $directory . '/Models';
                    if ($fs->isDirectory($modelsDir)) {
                        $mapping[$modelsDir] = $context['_meta']['namespace'] . '\\' . $directory . '\\Models\\';
                    }
                }
            }
        }

        return collect((new Finder)->in(array_keys($mapping))->files()->name('*.php'))
            ->map(function ($model) use ($mapping) {
                $key = Str::substr($model, 0, Str::position($model, 'Models'));
                $namespace = $mapping[$key];

                return $namespace . Str::replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($model, $key . DIRECTORY_SEPARATOR)
                );
            })->when(!empty($except), function ($models) use ($except) {
                return $models->reject(function ($model) use ($except) {
                    return in_array($model, $except);
                });
            })->filter(function ($model) {
                return class_exists($model);
            })->filter(function ($model) {
                return $this->isPrunable($model);
            })->values();
    }
}
