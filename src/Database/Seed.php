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
        $class = $this->argument('class') ?? $this->option('class');
        if (empty($class)) {
            $class = 'DatabaseSeeder';
        }

        ['name' => $module, 'meta' => $meta] = $this->getModule();
        if ($module) {
            $class = $meta['namespace'] . $module . '\\Database\\Seeders\\' . $class;
        } else {
            $path = $this->laravel->basePath() . '/database/seeders/';
            $path .= Str::replace('\\', '/', $class) . '.php';
            $this->laravel['files']->requireOnce($path);

            if (!Str::contains($class, '\\')) {
                $class = 'Database\\Seeders\\' . $class;
            }
            if ($class === 'Database\\Seeders\\DatabaseSeeder' && !class_exists($class)) {
                $class = 'DatabaseSeeder';
            }
        }

        return $this->laravel->make($class)
                        ->setContainer($this->laravel)
                        ->setCommand($this);
    }
}
