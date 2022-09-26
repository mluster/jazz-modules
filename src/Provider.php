<?php

declare(strict_types=1);

namespace Jazz\Modules;

use Illuminate\Support\ServiceProvider;
use DirectoryIterator;

class Provider extends ServiceProvider
{
    public function boot(): void
    {
        $config = dirname(__DIR__) . '/config/modules.php';
        $this->publishes([$config => config_path('modules.php')]);
    }


    public function register(): void
    {
        $config = dirname(__DIR__) . '/config/modules.php';
        $this->mergeConfigFrom($config, 'modules');

        $list = config('modules.contexts');
        foreach ($list as $key => $options) {
            if (!$options['active']) {
                continue;
            }
            if ($options['autoload']) {
                $this->registerViaPath(
                    $options['path'],
                    $options['namespace'],
                    $options['provider']
                );
            }
        }
    }

    protected function registerProvider(string $namespace, string $name, string $provider = 'Provider'): void
    {
        $class = $namespace . $name . '\\' . $provider;
        if (class_exists($class)) {
            $this->app->register($class);
        }
    }

    protected function registerViaPath(string $path, string $namespace, string $provider): void
    {
        $path = $this->app->basePath($path);
        if (is_dir($path)) {
            $dir = new DirectoryIterator($path);
            foreach ($dir as $file) {
                if ($file->isDot() || !$file->isDir()) {
                    continue;
                }
                $this->registerProvider($namespace, $file->getFilename(), $provider);
            }
        }
    }
}
