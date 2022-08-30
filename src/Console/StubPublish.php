<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Foundation\Console\StubPublishCommand;
use Illuminate\Filesystem\Filesystem;
use DirectoryIterator;

class StubPublish extends StubPublishCommand
{
    protected $signature = 'stub:publish
                    {--existing : Publish and overwrite only the files that have already been published}
                    {--force : Overwrite any existing files}
                    {--useLaravel : Use Laravel stubs instead of Jazz}';

    public function handle(): void
    {
        if ($this->option('useLaravel')) {
            parent::handle();
            return;
        }

        $existing = $this->option('existing');
        $force = $this->option('force');

        $fromPath = dirname(__DIR__, 2) . '/stubs';
        $toPath = $this->laravel->basePath('stubs') . '/';
        if (!is_dir($toPath)) {
            (new Filesystem())->makeDirectory($toPath);
        }

        $dir = new DirectoryIterator($fromPath);
        foreach ($dir as $file) {
            if ($file->isDot() || $file->isDir()) {
                continue;
            }

            $to = $toPath . '/' . $file->getFilename();
            if ((!$existing && (!file_exists($to) || $force)) || ($existing && file_exists($to))) {
                file_put_contents($to, file_get_contents($file->getPathname()));
            }
        }

        $this->info('Stubs published successfully');
    }
}
