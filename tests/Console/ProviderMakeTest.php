<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class ProviderMakeTest extends ATestCase
{
    protected string $myCommand = 'make:provider';
    protected string $myComponent = 'Providers';

    public function provider(): array
    {
        return [
            ['MyProvider', null, null],

            ['MyProvider', self::MODULE, null],
            ['MyModuleProvider', self::MODULE, ['--type' => 'module']],
            ['MyAuthProvider', self::MODULE, ['--type' => 'auth']],
            ['MyRouterProvider', self::MODULE, ['--type' => 'router']],
            ['MyEventProvider', self::MODULE, ['--type' => 'event']],
            ['MyProvider', null, ['--context' => 'default']],

            ['MyProvider', 'sample.Sandbox', null],
            ['MyModuleProvider', 'sample.Sandbox', ['--type' => 'module']],
            ['MyAuthProvider', 'sample.Sandbox', ['--type' => 'auth']],
            ['MyRouterProvider', 'sample.Sandbox', ['--type' => 'router']],
            ['MyEventProvider', 'sample.Sandbox', ['--type' => 'event']],
            ['MyProvider', null, ['--context' => 'sample']],
        ];
    }

    protected function assertMyFileExists(string $name, ?string $module): void
    {
        if (Arr::exists($this->myArgs, '--context')) {
            $context = $this->myArgs['--context'];
            $meta = Config::get('modules.' . $context . '._meta');

            $path = self::SANDBOX . '/' . $meta['path'] . '/' . $name . '.php';
            $this->assertFileExists($path, $path . ' not found');
            require_once($path);
            return;
        }
        parent::assertMyFileExists($name, $module);
    }
}
