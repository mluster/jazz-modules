<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Support\Facades\Config;

trait TMarkdownTemplate
{
    use TModuleContext;

    protected function writeMarkdownTemplate(): void
    {
        ['name' => $module, 'meta' => $meta] = $this->getModule();
        if ($module) {
            $path = $this->laravel->basePath() . '/' . $meta['path'] . '/' . $module . '/';
            $path .= $meta['assets'] . '/' . $meta['views'] . '/';

            if (!$this->files->isDirectory($path)) {
                $this->files->makeDirectory($path, 0755, true);
            }

            $path .= str_replace('.', '/', $this->option('markdown')) . '.blade.php';
            $this->files->put($path, file_get_contents($this->getStubFile('markdown.stub')));
            return;
        }
        parent::writeMarkdownTemplate();
    }
}
