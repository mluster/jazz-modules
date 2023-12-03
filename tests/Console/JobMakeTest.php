<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

use Illuminate\Contracts\Queue\ShouldQueue;

class JobMakeTest extends ATestCase
{

    protected string $myCommand = 'make:job';
    protected string $myComponent = 'Jobs';

    public function provider(): array
    {
        return [
            ['MyJob', null, ['--sync' => false], [], []],
            ['MySyncJob', null, ['--sync' => true], [], []],

            ['MyJob', self::MODULE, ['--sync' => false], [], []],
            ['MySyncJob', self::MODULE, ['--sync' => true], [], []],

            ['MyJob', self::SAMPLE_MODULE, ['--sync' => false], [], []],
            ['MySyncJob', self::SAMPLE_MODULE, ['--sync' => true], [], []],
        ];
    }

    protected function assertions(string $name, ?string $module, array $myFile, array $myClass): void
    {
        $args = $this->myArgs;
        parent::assertions($name, $module, $myFile, $myClass);

        $class = $this->getMyClass($name, $module);
        $implements = is_subclass_of($class, ShouldQueue::class);
        $this->assertTrue($args['--sync'] ? $implements : !$implements);
    }
}
