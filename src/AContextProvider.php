<?php

declare(strict_types=1);

namespace Jazz\Modules;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

class AContextProvider extends ServiceProvider
{
    protected function registerModuleProviders(): void
    {
        $fs = new Filesystem();
        $list = $fs->files(__DIR__);
        foreach ($list as $file) {
            if (!$fs->exists(__DIR__ . '/' . $file->getFilename() . '/Providers/Provider.php')) {
                continue;
            }

            $class = __NAMESPACE__ . '\\' . $file->getFilename() . '\\Providers\\Provider';
            $this->app->register($class);
        }
    }
}
