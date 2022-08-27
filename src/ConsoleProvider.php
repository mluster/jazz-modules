<?php

declare(strict_types=1);

namespace Jazz\Modules;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Jazz\Modules\Console\CastMake;
use Jazz\Modules\Console\ChannelMake;
use Jazz\Modules\Console\ComponentMake;
use Jazz\Modules\Console\ConsoleMake;
use Jazz\Modules\Console\EventMake;
use Jazz\Modules\Console\ExceptionMake;

class ConsoleProvider extends ServiceProvider implements DeferrableProvider
{
    protected array $commands = [
        'ConsoleMake' => 'command.console.make',
        'CastMake' => 'command.cast.make',
        'ChannelMake' => 'command.channel.make',
        'ComponentMake' => 'command.component.make',
        'EventMake' => 'command.event.make',
        'ExceptionMake' => 'command.exception.make',
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
    protected function registerConsoleMake(): void
    {
        $this->app->singleton('command.console.make', static function ($app) {
            return new ConsoleMake($app['files']);
        });
    }

    protected function registerCastMake(): void
    {
        $this->app->singleton('command.cast.make', function ($app) {
            return new CastMake($app['files']);
        });
    }

    protected function registerChannelMake(): void
    {
        $this->app->singleton('command.channel.make', static function ($app) {
            return new ChannelMake($app['files']);
        });
    }

    protected function registerComponentMake(): void
    {
        $this->app->singleton('command.component.make', static function ($app) {
            return new ComponentMake($app['files']);
        });
    }

    protected function registerEventMake(): void
    {
        $this->app->singleton('command.event.make', static function ($app) {
            return new EventMake($app['files']);
        });
    }

    protected function registerExceptionMake(): void
    {
        $this->app->singleton('command.exception.make', static function ($app) {
            return new ExceptionMake($app['files']);
        });
    }
}
