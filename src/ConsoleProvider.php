<?php

declare(strict_types=1);

namespace Jazz\Modules;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Jazz\Modules\Console\ConsoleMake;
use Jazz\Modules\Console\ResourceMake;

class ConsoleProvider extends ServiceProvider implements DeferrableProvider
{
    protected array $commands = [
        'ConsoleMake' => 'command.console.make',
        'ResourceMake' => 'command.resource.make',
    ];


    public function register(): void
    {
        foreach (array_keys($this->commands) as $command) {
            $method = 'register' . $command;
            call_user_func([$this, $method]);
        }
        $this->commands(array_values($this->commands));
    }

    public function provides(): array
    {
        return array_values($this->commands);
    }


    // Register Methods
    protected function registerConsoleMake()
    {
        $this->app->singleton('command.console.make', static function ($app) {
            return new ConsoleMake($app['files']);
        });
    }

    protected function registerResourceMake(): void
    {
        $this->app->singleton('command.resource.make', static function ($app) {
            return new ResourceMake($app['files']);
        });
    }
}
