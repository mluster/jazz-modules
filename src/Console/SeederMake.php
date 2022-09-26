<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class SeederMake extends SeederMakeCommand
{
    use TGenerator;

    protected function rootNamespace(): string
    {
        $ret = 'Database\Seeders';

        ['name' => $module, 'meta' => $meta] = $this->getModule();
        if ($module) {
            $ret = $meta['namespace'] . $module . '\\' . $ret;
        }

        return $ret;
    }

    protected function getPath($name): string
    {
        $name = Str::finish($name, 'Seeder');

        $path = $this->laravel->basePath() . '/';
        ['name' => $module, 'meta' => $meta] = $this->getModule();
        if ($module) {
            $path .= $meta['path'] . '/' . $module . '/Database/Seeders/';
        } else {
            $path .= 'database/seeders/';
        }
        $path .= str_replace('\\', '/', $name) . '.php';

        return $path;
    }

    protected function getStub(): string
    {
        return $this->getStubFile('seeder.stub');
    }

    protected function qualifyClass($name): string
    {
        return $name;
    }
}
