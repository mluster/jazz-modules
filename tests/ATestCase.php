<?php

declare(strict_types=1);

namespace JazzTest\Modules;

use Illuminate\Support\Str;
use Illuminate\Testing\PendingCommand;
use Orchestra\Testbench\TestCase;
use Jazz\Modules\Provider;
use Jazz\Modules\ConsoleProvider;
use DirectoryIterator;

abstract class ATestCase extends TestCase
{
    protected const SANDBOX = 'laravel';
    protected const APP_PATH = self::SANDBOX . '/app';
    protected const APP_NAMESPACE = 'App\\';

    protected array $sandboxPaths = [
        'bootstrap/cache',
        'app',
        'database/factories',
        'database/migrations',
        'database/seeders',
        'modules',
        'resources/views',
        'stubs',
        'tests/Feature',
        'tests/Unit',
    ];



    public function setUp(): void
    {
        parent::setUp();

        $clear = (bool) env('TEST_CLEAR_AT_SETUP', true);
        if ($clear) {
            foreach ($this->sandboxPaths as $path) {
                $this->sandboxClean($path);
            }
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $clear = (bool) env('TEST_CLEAR_AT_TEARDOWN', true);
        if ($clear) {
            foreach ($this->sandboxPaths as $path) {
                $this->sandboxClean($path);
            }
        }
    }



    protected function createArtisan(string $command, array $args = []): PendingCommand
    {
        return $this->artisan($command, $args)
            ->assertExitCode(0);
    }



    protected function getPackageProviders($app): array
    {
        return [
            Provider::class,
            ConsoleProvider::class,
        ];
    }

    protected function getBasePath(): string
    {
        return dirname(__DIR__) . '/laravel';
    }



    private function sandboxClean(string $path): void
    {
        $sandbox = dirname(__DIR__) . '/' . self::SANDBOX;
        if (!Str::contains($path, $sandbox)) {
            $path = $sandbox . '/' . $path;
        }

        if (!is_dir($path)) {
            return;
        }

        $dir = new DirectoryIterator($path);
        foreach ($dir as $file) {
            if ($file->isDot() || $file->getFilename() === '.gitignore') {
                continue;
            }

            if ($file->isDir()) {
                $this->sandboxClean($file->getRealPath());
                rmdir($file->getRealPath());
            } else {
                unlink($dir->getRealPath());
            }
        }
    }
}
