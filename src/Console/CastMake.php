<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Foundation\Console\CastMakeCommand;

class CastMake extends CastMakeCommand
{
    use TGenerator;

    protected function getStub(): string
    {
        return ($this->option('inbound'))
                ? $this->getStubFile('cast.inbound.stub')
                : $this->getStubFile('cast.stub');
    }
}
