<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

use JazzTest\Modules\ATestCase as ABaseTestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

abstract class ATestCase extends ABaseTestCase
{
    protected const MODULE = 'Sandbox';

    protected const SAMPLE = 'sample';
    protected const SAMPLE_MODULE = 'sample.' . self::MODULE;

    protected string $myCommand;
    protected string $myComponent;

    protected string $myModuleKey = '--module';
    protected string $myModuleName = 'Module';
    protected string $myModuleContext = 'default';
    protected string $myModuleNamespace = 'App\\Modules\\';
    protected string $myModulePath = 'app/Modules';

    protected array $myArgs = [];


    /**
     * Set Up
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->myModuleKey = '--' . Config::get('modules.key');
        $this->myModuleName = Config::get('modules.name');
        $this->myModuleContext = Config::get('modules.context');

        $key = 'modules.contexts.' . $this->myModuleContext . '._meta.';
        $this->myModuleNamespace = Config::get($key . 'namespace');
        $this->myModulePath = Config::get($key . 'path');
    }


    /**
     * Test RUN
     * @param string $name
     * @param ?string $module
     * @param ?array $args
     * @dataProvider provider
     */
    public function testRun(string $name, ?string $module, ?array $args, ?string $myFile, ?string $myClass): void
    {
        if ($name === null || $this->myCommand === null || $this->myComponent === null) {
            $this->markTestIncomplete();
        }

        $args = ($args ?? []);
        $args['name'] = $name;
        $args['--no-interaction'] = true;
        if ($module) {
            $args[$this->myModuleKey] = $module;
        }

        $this->myArgs = $args;
        $this->createArtisan($this->myCommand, $this->myArgs);
        $this->assertions($name, $module, $myFile, $myClass);
    }

    /**
     * Data Provider
     * @return array
     */
    abstract public function provider(): array;


    // ASSERTIONS
    /**
     * Assertions
     * @param string $name
     * @param ?string $module
     */
    protected function assertions(string $name, ?string $module, ?string $myFile, ?string $myClass): void
    {
        $this->assertMyFileExists($module, $myFile);
        $this->assertMyClassExists($module, $myClass);
    }

    /**
     * Assert File Exists
     * @param string $name
     * @param ?string $module
     */
    protected function assertMyFileExists(?string $module, ?string $file): void
    {
        $file = $this->getMyPath($file, $module);
        $this->assertFileExists($file, $file . ' MISSING');
        require_once($file);
    }

    /**
     * Assert Class Exists
     * @param string $name
     * @param ?string $module
     */
    protected function assertMyClassExists(?string $module, ?string $name): void
    {
        $class = $this->getMyClass($name, $module);
        $this->assertTrue(class_exists($class, false), $class . ' MISSING');
    }

    /**
     * Verify if METHOD is defined by CLASS
     * @param string $class
     * @param string $method
     * @param bool $expected
     */
    protected function assertMethodInClass(string $class, string $method, bool $expected): void
    {
        $exists = method_exists($class, $method);
        $this->assertTrue(($expected ? $exists : !$exists), $class . '::' . $method);
    }



    // HELPER METHODS
    /**
     * Returns PATH
     * @param string $className
     * @param string|null $module
     * @return string
     */
    protected function getMyPath(string $file, ?string $module): string
    {
        ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);

        $component = str_replace('.', '/', $this->myComponent);

        $ret = self::APP_PATH . '/';
        if ($module) {
            $ret = self::SANDBOX . '/' . $meta['path'] . '/' . $module . '/';
        }
        $ret .= $component . '/' . Str::replaceLast('.php', '', $file) . '.php';

        return $ret;
    }

    /**
     * Returns CLASS NAME with NAMESPACE
     * @param string $className
     * @param string|null $module
     * @return string
     */
    protected function getMyClass(string $className, ?string $module): string
    {
        ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);

        $component = str_replace('.', '\\', $this->myComponent);

        $ret = self::APP_NAMESPACE;
        if ($module) {
            $ret = $meta['namespace'] . $module . '\\';
        }
        $ret .= $component . '\\' . $className;

        return $ret;
    }

    /**
     * @return array{context: ?string, name: ?string, meta: ?array{namespace: string, path: string, provider: string, assets: string, active: bool, autoload: bool}}
     */
    protected function getMyModule(?string $module): array
    {
        $ret = [
            'context' => null,
            'name' => null,
            'meta' => null
        ];

        if ($module) {
            $context = Config::get('modules.context');
            if (Str::contains($module, '.')) {
                [$context, $module] = explode('.', $module);
            }

            $ret['context'] = $context;
            $ret['name'] = $module;
            $ret['meta'] = Config::get('modules.contexts.' . $context . '._meta');
        }

        return $ret;
    }
}
