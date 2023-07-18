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

    protected array $sandboxClean = [
        'bootstrap/cache',
    ];
    protected array $sandboxPaths = [
        'app',
        'database',
        'modules',
        'resources',
        'sample',
        'storage',
        'stubs',
        'tests',
        'vendor',
    ];



    public function setUp(): void
    {
        parent::setUp();

        $clear = (bool) env('TEST_CLEAR_AT_SETUP', true);
        if ($clear) {
            foreach ($this->sandboxClean as $path) {
                $this->sandboxClean($path);
            }
            foreach ($this->sandboxPaths as $path) {
                $this->sandboxRemove($path);
            }
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $clear = (bool) env('TEST_CLEAR_AT_TEARDOWN', true);
        if ($clear) {
            foreach ($this->sandboxClean as $path) {
                $this->sandboxClean($path);
            }
            foreach ($this->sandboxPaths as $path) {
                $this->sandboxRemove($path);
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
        return dirname(__DIR__) . '/' . self::SANDBOX;
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
    private function sandboxRemove(string $path): void
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
                $this->sandboxRemove($file->getRealPath());
                //rmdir($file->getRealPath());
            } else {
                unlink($dir->getRealPath());
            }
        }
        rmdir($path);
    }
}