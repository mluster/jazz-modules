<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

use Exception;

class ExceptionMakeTest extends ATestCase
{
    protected string $myCommand = 'make:exception';
    protected string $myComponent = 'Exceptions';

    public function provider(): array
    {
        return [
            ['MyException', null, [], 'MyException', 'MyException'],
            ['MyRenderException', null, ['--render' => true], 'MyRenderException', 'MyRenderException'],
            ['MyReportException', null, ['--report' => true], 'MyReportException', 'MyReportException'],
            ['MyRenderReportException', null, ['--render' => true, '--report' => true], 'MyRenderReportException', 'MyRenderReportException'],

            ['MyException', self::MODULE, [], 'MyException', 'MyException'],
            ['MyRenderException', self::MODULE, ['--render' => true], 'MyRenderException', 'MyRenderException'],
            ['MyReportException', self::MODULE, ['--report' => true], 'MyReportException', 'MyReportException'],
            ['MyRenderReportException', self::MODULE, ['--render' => true, '--report' => true], 'MyRenderReportException', 'MyRenderReportException'],

            ['MyException', self::SAMPLE_MODULE, [], 'MyException', 'MyException'],
            ['MyRenderException', self::SAMPLE_MODULE, ['--render' => true], 'MyRenderException', 'MyRenderException'],
            ['MyReportException', self::SAMPLE_MODULE, ['--report' => true], 'MyReportException', 'MyReportException'],
            ['MyRenderReportException', self::SAMPLE_MODULE, ['--render' => true, '--report' => true], 'MyRenderReportException', 'MyRenderReportException'],
        ];
    }

    protected function assertions(string $name, ?string $module, ?string $myFile, ?string $myClass): void
    {
        parent::assertions($name, $module, $myFile, $myClass);

        $args = $this->myArgs;
        $class = $this->getMyClass($name, $module);
        $this->assertTrue(is_subclass_of($class, Exception::class));
        $this->assertMethodInClass($class, 'render', isset($args['--render']));
        $this->assertMethodInClass($class, 'report', isset($args['--report']));
    }
}
