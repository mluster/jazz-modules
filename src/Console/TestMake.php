<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Foundation\Console\TestMakeCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class TestMake extends TestMakeCommand
{
    use TGenerator;

    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $name = Str::replaceFirst('\Tests', '', $name);
        $path = base_path('tests');

        $module = $this->option(Config::get('modules.key'));
        if ($module) {
            $path = $this->laravel->basePath() . '/' . Config::get('modules.path') . '/' . $module . '/Tests';
        }

        return $path . '/' . str_replace('\\', '/', $name) . '.php';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Tests' . ($this->option('unit') ? '\Unit' : '\Feature');
    }

    protected function getStub(): string
    {
        $stubFile = 'test.stub';
        if ($this->option('unit')) {
            $stubFile = 'test.unit.stub';
        }
        return $this->getStubFile($stubFile);
    }
}
