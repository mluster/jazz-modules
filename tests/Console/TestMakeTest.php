<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

use PHPUnit\Framework\TestCase;

class TestMakeTest extends ATestCase
{
    protected string $myCommand = 'make:test';
    protected string $myComponent = 'Tests';

    public function provider(): array
    {
        return [
            ['MyTest', null, null],
            ['MyTest', null, ['--unit' => null]],

            ['MyTest', self::MODULE, null],
            ['MyTest', self::MODULE, ['--unit' => null]],

            ['MyTest', 'sample.Sandbox', null],
            ['MyTest', 'sample.Sandbox', ['--unit' => null]],
        ];
    }

    protected function assertions(string $name, ?string $module): void
    {
        $file = $this->getMyPath($name, $module);
        $this->assertFileExists($file, $file . ' not found');
    }

    protected function getMyPath(string $className, ?string $module): string
    {
        ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);
        $component = str_replace('.', '/', $this->myComponent);

        $ret = self::SANDBOX . '/tests';
        if ($module !== null) {
            $ret = self::SANDBOX . '/' . $meta['path'] . '/' . $module . '/' . $component;
        }
        $ret .= (array_key_exists('--unit', $this->myArgs)) ? '/Unit' : '/Feature';
        $ret .= '/' . $className . '.php';

        return $ret;
    }

    protected function getMyClass(string $className, ?string $module): string
    {
        $component = str_replace('.', '\\', $this->myComponent);

        $ret = self::APP_NAMESPACE;
        if ($module !== null) {
            $ret = $this->myModuleNamespace . $module . '\\';
        }
        $ret .= $component . '\\';
        $ret .= (array_key_exists('--unit', $this->myArgs)) ? 'Unit\\' : 'Feature\\';
        $ret .= $className;

        return $ret;
    }
}
