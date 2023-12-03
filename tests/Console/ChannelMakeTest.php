<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

class ChannelMakeTest extends ATestCase
{
    protected string $myCommand = 'make:channel';
    protected string $myComponent = 'Broadcasting';

    public function provider(): array
    {
        return [
            ['MyChannel', null, null, ['MyChannel.php'], ['MyChannel']],
            ['MyChannel', self::MODULE, null, ['MyChannel.php'], ['MyChannel']],
            ['MyChannel', self::SAMPLE_MODULE, null, ['MyChannel.php'], ['MyChannel']],
        ];
    }
}
