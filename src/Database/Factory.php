<?php

declare(strict_types=1);

namespace Jazz\Modules\Database;

use Illuminate\Database\Eloquent\Factories\Factory as LaravelFactory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

abstract class Factory extends LaravelFactory
{
    public static function resolveFactory(string $model): string
    {
        $ret = null;
        if (Str::startsWith($model, 'App\\')) {
            $model = Str::startsWith($model, 'App\\Models\\')
                ? Str::after($model, 'App\\Models\\')
                : Str::after($model, 'App\\');
            $ret = static::$namespace . $model;
        } else {
            $found = false;
            $contexts = Config::get('modules.contexts');
            foreach ($contexts as $options) {
                $meta = $options['_meta'];
                if (Str::startsWith($model, $meta['namespace'])) {
                    $found = true;
                    $ret = Str::replace('\\Models\\', '\\' . $meta['factories']['namespace'], $model);

                    if (!class_exists($ret . 'Factory')) {
                        $path = Str::replace($meta['namespace'], $meta['path'] . '/', $ret);
                        $path = Str::replace(
                            $meta['factories']['namespace'],
                            $meta['assets'] . '/' . $meta['factories']['path'] . '/',
                            $path
                        );
                        require_once(App::basePath(Str::replace('\\', '/', $path)) . 'Factory.php');
                    }
                    break;
                }
            }
            if (!$found) {
                $ret = Str::replace('\\Models\\', '\\Database\\Factories\\', $model);
            }
        }
        return $ret . 'Factory';
    }

    public static function resolveModel(LaravelFactory $factory): string
    {
        $name = Str::replaceLast('Factory', '', get_class($factory));
        if (Str::startsWith('Database\\Factories\\', $name)) {
            $model = 'App\\Models\\' . Str::after($name, 'Database\\Factories\\');
        } else {
            $found = false;
            $contexts = Config::get('modules.contexts');
            foreach ($contexts as $options) {
                $meta = $options['_meta'];
                if (Str::startsWith($name, $meta['namespace'])) {
                    $found = true;
                    $model = Str::replace($meta['factories']['namespace'], 'Models//', $name);
                    break;
                }
            }

            if (!$found) {
                $model = Str::replace('\\Database\\Factories\\', '\\Models\\', $name);
            }
        }
        return $model;
    }
}
