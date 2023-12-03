<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

class EventMakeTest extends ATestCase
{
    protected string $myCommand = 'make:event';
    protected string $myComponent = 'Events';

    public function provider(): array
    {
        return [
            ['MyEvent', null, null, [], []],
            ['MyEvent', self::MODULE, null, [], []],
            ['MyEvent', self::SAMPLE_MODULE, null, [], []],

            ['App\\Events\\MyFullEvent', null, null, ['MyFullEvent'], ['MyFullEvent']],
            ['Module\\Sandbox\\Events\\MyFullEvent', self::MODULE, null, ['MyFullEvent'], ['MyFullEvent']],
            ['Sample\\Sandbox\\Events\\MyFullEvent', self::SAMPLE_MODULE, null, ['MyFullEvent'], ['MyFullEvent']],
        ];
    }
}
