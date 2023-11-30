<?php

declare(strict_types=1);

namespace Jazz\Modules;

use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

abstract class AEventProvider extends EventServiceProvider
{
    protected string $context;
    protected string $module;

    public function discoverEvents(): array
    {
        $basePath = $this->app->path();
        $path = $this->app->path('Listeners');
        $namespace = $this->app->getNamespace();

        $key = 'modules.contexts.' . $this->context . '.' . $this->module . '._meta';
        if (Config::has($key)) {
            $meta = Config::get($key);

            $basePath = base_path($meta['path']);
            $path = $basePath . '/Listeners';
            $namespace = $meta['namespace'];
        }

        return collect([$path])
            ->reject(function ($dir) {
                return ! is_dir($dir);
            })
            ->reduce(function ($discovered, $directory) use ($basePath, $namespace){
                return array_merge_recursive(
                    $discovered,
                    DiscoverEvents::within($directory, $basePath, $namespace)
                );
            }, []);
    }
}
