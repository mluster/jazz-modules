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
use Jazz\Modules\Console\FactoryMake;
use Jazz\Modules\Console\JobMake;
use Jazz\Modules\Console\ListenerMake;
use Jazz\Modules\Console\MiddlewareMake;
use Jazz\Modules\Console\NotificationMake;
use Jazz\Modules\Console\ObserverMake;
use Jazz\Modules\Console\PolicyMake;

class ConsoleProvider extends ServiceProvider implements DeferrableProvider
{
    protected array $commands = [
        'ConsoleMake' => 'command.console.make',
        'CastMake' => 'command.cast.make',
        'ChannelMake' => 'command.channel.make',
        'ComponentMake' => 'command.component.make',
        'EventMake' => 'command.event.make',
        'ExceptionMake' => 'command.exception.make',
        'FactoryMake' => 'command.factory.make',
        'JobMake' => 'command.job.make',
        'ListenerMake' => 'command.listener.make',
        'MiddlewareMake' => 'command.middleware.make',
        'NotificationMake' => 'command.notification.make',
        'ObserverMake' => 'command.observer.make',
        'PolicyMake' => 'command.policy.make',
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

    protected function registerFactoryMake(): void
    {
        $this->app->singleton('command.factory.make', static function ($app) {
            return new FactoryMake($app['files']);
        });
    }

    protected function registerJobMake(): void
    {
        $this->app->singleton('command.job.make', static function ($app) {
            return new JobMake($app['files']);
        });
    }

    protected function registerListenerMake(): void
    {
        $this->app->singleton('command.listener.make', static function ($app) {
            return new ListenerMake($app['files']);
        });
    }

    protected function registerMiddlewareMake(): void
    {
        $this->app->singleton('command.middleware.make', static function ($app) {
            return new MiddlewareMake($app['files']);
        });
    }

    protected function registerNotificationMake(): void
    {
        $this->app->singleton('command.notification.make', static function ($app) {
            return new NotificationMake($app['files']);
        });
    }

    protected function registerObserverMake(): void
    {
        $this->app->singleton('command.observer.make', static function ($app) {
            return new ObserverMake($app['files']);
        });
    }

    protected function registerPolicyMake(): void
    {
        $this->app->singleton('command.policy.make', static function ($app) {
            return new PolicyMake($app['files']);
        });
    }
}
