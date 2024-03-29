<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

use Illuminate\Console\Command;

class ConsoleMakeTest extends ATestCase
{
    protected string $myCommand = 'make:command';
    protected string $myComponent = 'Console.Commands';


    public function provider(): array
    {
        return [
            ['MyCommand', null, null, [], []],
            ['MyCommand', self::MODULE, null, [], []],
            ['MyCommand', self::SAMPLE_MODULE, null, [], []],
        ];
    }

    protected function assertions(string $name, ?string $module, array $myFile, array $myClass): void
    {
        parent::assertions($name, $module, $myFile, $myClass);

        $class = $this->getMyClass($name, $module);
        $this->assertTrue(is_subclass_of($class, Command::class));
    }
}
