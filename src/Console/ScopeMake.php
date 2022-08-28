<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Foundation\Console\ScopeMakeCommand;

class ScopeMake extends ScopeMakeCommand
{
    use TGenerator;

    protected function getStub(): string
    {
        return $this->getStubFile('scope.stub');
    }
}
