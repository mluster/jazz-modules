<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Foundation\Console\ProviderMakeCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class ProviderMake extends ProviderMakeCommand
{
    use TGenerator {
        buildClass as myBuildClass;
        getOptions as myGetOptions;
        rootNamespace as myRootNamespace;
        getPath as myGetPath;
    }

    protected function buildClass($name): string
    {
        $stub = $this->myBuildClass($name);

        ['name' => $module, 'context' => $context] = $this->getModule();
        $stub = Str::replace(['{{context}}', '{{ context }}'], $context, $stub);
        return Str::replace(['{{module}}', '{{ module }}'], $module, $stub);
    }

    protected function rootNamespace(): string
    {
        $context = $this->option('context');
        if (Config::has('modules.contexts.' . $context)) {
            $meta = Config::get('modules.contexts.' . $context . '._meta');
            return $meta['namespace'];
        }
        return $this->myRootNamespace();
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $context = $this->option('context');
        if (Config::has('modules.contexts.' . $context)) {
            return $rootNamespace;
        }
        return parent::getDefaultNamespace($rootNamespace);
    }

    protected function getPath($name): string
    {
        $context = $this->option('context');
        if (Config::has('modules.contexts.' . $context)) {
            $meta = Config::get('modules.contexts.' . $context . '._meta');
            $path = $this->laravel->basePath() . '/' . $meta['path'] . '/';
            $path .= str_replace($meta['namespace'], '', $name) . '.php';
            return $path;
        }
        return $this->myGetPath($name);
    }

    protected function getStub(): string
    {
        $ret = $this->getStubFile('provider.stub');
        if ($this->option('type')) {
            switch ($this->option('type')) {
                case 'module':
                    $ret = $this->getStubFile('provider.module.stub');
                    break;
                case 'auth':
                    $ret = $this->getStubFile('provider.auth.stub');
                    break;
                case 'router':
                    $ret = $this->getStubFile('provider.router.stub');
                    break;
                case 'event':
                    $ret = $this->getStubFile('provider.event.stub');
                    break;
            }
        }
        if ($this->option('context')) {
            $ret = $this->getStubFile('provider.context.stub');
        }
        return $ret;
    }

    protected function getOptions(): array
    {
        $ret = $this->myGetOptions();
        $ret[] = ['context', null, InputOption::VALUE_OPTIONAL, 'Install a ContextProvider in given Context (overrides other options)'];
        $ret[] = ['type', null, InputOption::VALUE_OPTIONAL, 'Specify Provider Type [module|auth|router]'];
        return $ret;
    }
}
