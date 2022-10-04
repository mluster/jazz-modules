<?php

declare(strict_types=1);

namespace Jazz\Modules;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

class AContextProvider extends ServiceProvider
{
    final protected function registerModuleProviders(string $path, string $namespace): void
    {
        $fs = new Filesystem();
        $list = $fs->directories($path);
        foreach ($list as $file) {
            if (!$fs->exists($file . '/Providers/Provider.php')) {
                continue;
            }

            $class = $namespace . '\\' . basename($file) . '\\Providers\\Provider';
            $this->app->register($class);
        }
    }
}
