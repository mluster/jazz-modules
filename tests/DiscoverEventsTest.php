<?php

declare(strict_types=1);

namespace JazzTest\Modules;

use Jazz\Modules\DiscoverEvents;
use SplFileInfo;

class DiscoverEventsTest extends ATestCase
{
    /**
     * @dataProvider provider
     */
    public function testClassFromFile(string $file, string $basePath, string $namespace, string $expected)
    {
        $splFile = new SplFileInfo($file);
        $this->assertEquals($expected, DiscoverEvents::classFromFile($splFile, $basePath, $namespace));
    }

    public function provider(): array
    {
        $base = dirname(__DIR__) . '/src';
        return [
            [$base . '/ConsoleProvider.php', $base, 'Jazz\\Modules\\', 'Jazz\\Modules\\ConsoleProvider'],
            [$base . '/Console/ProviderMake.php', $base, 'Jazz\\Modules\\', 'Jazz\\Modules\\Console\\ProviderMake'],
        ];
    }
}
