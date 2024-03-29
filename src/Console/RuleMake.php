<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Foundation\Console\RuleMakeCommand;

class RuleMake extends RuleMakeCommand
{
    use TGenerator;

    protected function getStub(): string
    {
        $stub = 'rule.stub';
        if ($this->option('implicit')) {
            $stub = 'rule.implicit.stub';
        }
        return $this->getStubFile($stub);
    }
}