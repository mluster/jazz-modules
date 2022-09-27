<?php

declare(strict_types=1);

namespace Jazz\Modules\Database;

use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Jazz\Modules\Console\TModuleContext;
use Jazz\Modules\Console\TOptions;

class Seed extends SeedCommand
{
    use TOptions;
    use TModuleContext;

    protected function getSeeder(): Seeder
    {
        $name = $this->argument('class') ?? $this->option('class');
        if (empty($name)) {
            $name = 'DatabaseSeeder';
        }

        ['name' => $module, 'meta' => $meta] = $this->getModule();

        $path = $this->laravel->basePath() . '/';
        if ($module) {
            $path .= $meta['path'] . '/' . $module . '/' . $meta['assets'] . '/' . $meta['seeders']['path'] . '/';
            $class = $meta['namespace'] . $module . '\\' . $meta['seeders']['namespace'] . $name;
        } else {
            $path .= 'database/seeders/';

            $class = $name;
            if (!Str::contains($name, '\\')) {
                $class = 'Database\\Seeders\\' . $name;
            }
            if ($class === 'Database\\Seeders\\DatabaseSeeder' && !class_exists($class)) {
                $class = 'DatabaseSeeder';
            }
        }
        $path .= Str::replace('\\', '/' , $name) . '.php';
        $this->laravel['files']->requireOnce($path);

        return $this->laravel->make($class)
                        ->setContainer($this->laravel)
                        ->setCommand($this);
    }
}
