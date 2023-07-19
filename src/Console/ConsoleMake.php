<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Foundation\Console\ConsoleMakeCommand;
use Illuminate\Support\Str;

class ConsoleMake extends ConsoleMakeCommand
{
    use TGenerator {
        TGenerator::buildClass as myBuildClass;
    }

    protected function getStub(): string
    {
        return $this->getStubFile('console.stub');
    }

    protected function buildClass($name): string
    {
        $stub = $this->myBuildClass($name);
        $command = $this->option('command') ?: 'app:' . Str::of($name)->classBasename()->kebab()->value();
        return $this->replaceCommand($stub, $command);
    }
}