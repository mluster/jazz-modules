<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

class CastMakeTest extends ATestCase
{
    protected string $myCommand = 'make:cast';
    protected string $myComponent = 'Casts';

    public function provider(): array
    {
        return [
            ['MyCast', null, null, 'MyCast', 'MyCast'],
            ['MyCast', self::MODULE, null, 'MyCast', 'MyCast'],
            ['MyCast', self::SAMPLE_MODULE, null, 'MyCast', 'MyCast'],
            ['MyInboundCast', null, ['--inbound' => true], 'MyInboundCast', 'MyInboundCast'],
            ['MyInboundCast', self::MODULE, ['--inbound' => true], 'MyInboundCast', 'MyInboundCast'],
            ['MyInboundCast', self::SAMPLE_MODULE, ['--inbound' => true], 'MyInboundCast', 'MyInboundCast'],
        ];
    }
}
