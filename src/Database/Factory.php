<?php

declare(strict_types=1);

namespace Jazz\Modules\Database;

use Illuminate\Database\Eloquent\Factories\Factory as LaravelFactory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

abstract class Factory extends LaravelFactory
{
    public static function resolveFactory(string $model): string
    {
        if (Str::startsWith($model, 'App\\')) {
            $model = Str::startsWith($model, 'App\\Models\\')
                ? Str::after($model, 'App\\Models\\')
                : Str::after($model, 'App\\');
            $ret = static::$namespace . $model;
        } else {
            $ret = Str::replace('\\Models\\', '\\Database\\Factories\\', $model);
        }
        return $ret . 'Factory';
    }

    public static function resolveModel(LaravelFactory $factory): string
    {
        $name = Str::replaceLast('Factory', '', get_class($factory));
        if (Str::startsWith('Database\\Factories\\', $name)) {
            $model = 'App\\Models\\' . Str::after($name, 'Database\\Factories\\');
        } else {
            $model = Str::replace('\\Database\\Factories\\', '\\Models\\', $name);
        }
        return $model;
    }
}
