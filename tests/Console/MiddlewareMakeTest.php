<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

class MiddlewareMakeTest extends ATestCase
{
    protected string $myCommand = 'make:middleware';
    protected string $myComponent = 'Http.Middleware';

    public function provider(): array
    {
        return [
            ['MyMiddleware', null, null, [], []],
            ['MyMiddleware', self::MODULE, null, [], []],
            ['MyMiddleware', self::SAMPLE_MODULE, null, [], []],
        ];
    }

    protected function assertions(string $name, ?string $module, array $myFile, array $myClass): void
    {
        parent::assertions($name, $module, $myFile, $myClass);

        $class = $this->getMyClass($name, $module);
        $this->assertMethodInClass($class, 'handle', true);
    }
}
