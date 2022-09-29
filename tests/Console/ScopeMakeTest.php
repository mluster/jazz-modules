<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

class ScopeMakeTest extends ATestCase
{
    protected string $myCommand = 'make:scope';
    protected string $myComponent = 'Scopes';

    public function provider(): array
    {
        return [
            ['MyScope', null, null],
            ['MyScope', self::MODULE, null],
            ['MyScope', 'sample.Sandbox', null],
        ];
    }
}
