<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Foundation\Console\ProviderMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ProviderMake extends ProviderMakeCommand
{
    use TGenerator;

    protected function getStub(): string
    {
        return $this->getStubFile('provider.stub');
    }
}
