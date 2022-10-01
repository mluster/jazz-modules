<?php

declare(strict_types=1);

namespace Jazz\Modules;

use Illuminate\Support\Facades\Config;
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
        $this->app->instance('config', new ConfigRepository(Config::all()));
        Config::clearResolvedInstance('config');

        $list = Config::get('modules.contexts');
        if (is_array($list)) {
            foreach ($list as $key => $options) {
                $meta = $options['_meta'];
                if (!$meta['active']) {
                    continue;
                }
                if ($meta['autoload'] && $meta['provider'] !== null) {
                    $class = $meta['namespace'] . $meta['provider'];
                    if (class_exists($class)) {
                        $this->app->register($class);
                    }
                }
            }
        }
    }
}
