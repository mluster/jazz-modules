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
    public function testRun(string $name, ?string $module, ?array $args, array $myFile, array $myClass): void
    {
        if ($this->myCommand === null || $this->myComponent === null) {
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
     * @param array<string> $myFile
     * @param array<string> $myClass
     */
    protected function assertions(string $name, ?string $module, array $myFile, array $myClass): void
    {
        foreach ($myFile as $file) {
            $this->assertMyFileExists($file, $module);
        }
        foreach ($myClass as $name) {
            $this->assertMyClassExists($module, $name);
        }
    }

    protected function assertMyFileExists(string $file, ?string $module): void
    {
        $file = $this->getMyPath($file, $module);
        $this->assertFileExists($file, $file . ' MISSING');
        require_once($file);
    }

    protected function assertMyClassExists(string $name, ?string $module): void
    {
        $class = $this->getMyClass($name, $module);
        $this->assertTrue(class_exists($class, false), $class . ' MISSING');
    }

    protected function assertMethodInClass(string $class, string $method, bool $expected): void
    {
        $exists = method_exists($class, $method);
        $this->assertTrue(($expected ? $exists : !$exists), $class . '::' . $method);
    }



    // HELPER METHODS
    protected function getMyPath(string $file, ?string $module): string
    {
        ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);

        $component = Str::replace('.', '/', $this->myComponent);
        $file = Str::replaceLast('.php', '', $file) . '.php';

        $ret = self::SANDBOX . '/';
        if (!file_exists($ret . $file)) {
            $ret .= ($module)
                ? $meta['path'] . '/' . $module . '/'
                : self::APP_PATH . '/';
            if (!file_exists($ret . $file)) {
                $ret .= $component . '/';
            }
        }
        return $ret . $file;
    }

    protected function getMyClass(string $className, ?string $module): string
    {
        ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);

        $component = Str::replace('.', '\\', $this->myComponent);

        $ret = $className;
        if (!class_exists($className, false)) {
            $ret = ($module)
                ? $meta['namespace'] . $module . '\\'
                : self::APP_NAMESPACE;
            if (!class_exists($ret . $className)) {
                $ret .= $component . '\\';
            }
        }
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
