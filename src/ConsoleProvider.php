<?php

declare(strict_types=1);

namespace Jazz\Modules;

use Illuminate\Database\Eloquent\Factories\Factory as LaravelFactory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Jazz\Modules\Console\CastMake;
use Jazz\Modules\Console\ChannelMake;
use Jazz\Modules\Console\ComponentMake;
use Jazz\Modules\Console\ConsoleMake;
use Jazz\Modules\Console\ControllerMake;
use Jazz\Modules\Console\EventMake;
use Jazz\Modules\Console\ExceptionMake;
use Jazz\Modules\Console\JobMake;
use Jazz\Modules\Console\ListenerMake;
use Jazz\Modules\Console\MailMake;
use Jazz\Modules\Console\MiddlewareMake;
use Jazz\Modules\Console\ModelMake;
use Jazz\Modules\Console\ModelPrune;
use Jazz\Modules\Console\ModelShow;
use Jazz\Modules\Console\NotificationMake;
use Jazz\Modules\Console\ObserverMake;
use Jazz\Modules\Console\PolicyMake;
use Jazz\Modules\Console\ProviderMake;
use Jazz\Modules\Console\RequestMake;
use Jazz\Modules\Console\ResourceMake;
use Jazz\Modules\Console\RuleMake;
use Jazz\Modules\Console\ScopeMake;
use Jazz\Modules\Console\TestMake;
use Jazz\Modules\Database\Factory;
use Jazz\Modules\Database\Migration;
use Jazz\Modules\Console\MigrationMake;
use Jazz\Modules\Console\FactoryMake;
use Jazz\Modules\Console\SeederMake;
use Jazz\Modules\Database\Seed;
use Jazz\Modules\Console\StubPublish;
use Illuminate\Foundation\Console\CastMakeCommand;
use Illuminate\Foundation\Console\ChannelMakeCommand;
use Illuminate\Foundation\Console\ComponentMakeCommand;
use Illuminate\Foundation\Console\ConsoleMakeCommand;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Foundation\Console\EventMakeCommand;
use Illuminate\Foundation\Console\ExceptionMakeCommand;
use Illuminate\Foundation\Console\JobMakeCommand;
use Illuminate\Foundation\Console\ListenerMakeCommand;
use Illuminate\Foundation\Console\MailMakeCommand;
use Illuminate\Routing\Console\MiddlewareMakeCommand;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Foundation\Console\NotificationMakeCommand;
use Illuminate\Foundation\Console\ObserverMakeCommand;
use Illuminate\Foundation\Console\PolicyMakeCommand;
use Illuminate\Foundation\Console\ProviderMakeCommand;
use Illuminate\Foundation\Console\RequestMakeCommand;
use Illuminate\Foundation\Console\ResourceMakeCommand;
use Illuminate\Foundation\Console\RuleMakeCommand;
use Illuminate\Foundation\Console\ScopeMakeCommand;
use Illuminate\Foundation\Console\TestMakeCommand;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Database\Console\PruneCommand;
use Illuminate\Database\Console\ShowModelCommand;
use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Foundation\Console\StubPublishCommand;

class ConsoleProvider extends ServiceProvider implements DeferrableProvider
{
    protected array $commands = [
        'ConsoleMake' => ConsoleMakeCommand::class,
        'CastMake' => CastMakeCommand::class,
        'ChannelMake' => ChannelMakeCommand::class,
        'ComponentMake' => ComponentMakeCommand::class,
        'ControllerMake' => ControllerMakeCommand::class,
        'EventMake' => EventMakeCommand::class,
        'ExceptionMake' => ExceptionMakeCommand::class,
        'JobMake' => JobMakeCommand::class,
        'ListenerMake' => ListenerMakeCommand::class,
        'MailMake' => MailMakeCommand::class,
        'MiddlewareMake' => MiddlewareMakeCommand::class,
        'ModelMake' => ModelMakeCommand::class,
        'NotificationMake' => NotificationMakeCommand::class,
        'ObserverMake' => ObserverMakeCommand::class,
        'PolicyMake' => PolicyMakeCommand::class,
        'ProviderMake' => ProviderMakeCommand::class,
        'RequestMake' => RequestMakeCommand::class,
        'ResourceMake' => ResourceMakeCommand::class,
        'RuleMake' => RuleMakeCommand::class,
        'ScopeMake' => ScopeMakeCommand::class,
        'TestMake' => TestMakeCommand::class,

        'MigrationMake' => MigrateMakeCommand::class,
        'FactoryMake' => FactoryMakeCommand::class,
        'SeederMake' => SeederMakeCommand::class,
        'Seed' => SeedCommand::class,

        'StubPublish' => StubPublishCommand::class,

        'ModelShow' => ShowModelCommand::class,
        'ModelPrune' => PruneCommand::class,
    ];


    public function register(): void
    {
        if (Config::has('modules')) {
            foreach (array_keys($this->commands) as $command) {
                $method = 'register' . $command;
                call_user_func([$this, $method]);
            }

            LaravelFactory::guessFactoryNamesUsing(function (string $model) {
                return Factory::resolveFactory($model);
            });
            LaravelFactory::guessModelNamesUsing(function (LaravelFactory $factory) {
                return Factory::resolveModel($factory);
            });
        }
    }

    public function provides(): array
    {
        if (Config::has('modules')) {
            return array_values($this->commands);
        }
        return [];
    }


    // Register Methods
    protected function registerConsoleMake(): void
    {
        $this->app->singleton(ConsoleMakeCommand::class, static function ($app) {
            return new ConsoleMake($app['files']);
        });
    }

    protected function registerCastMake(): void
    {
        $this->app->singleton(CastMakeCommand::class, function ($app) {
            return new CastMake($app['files']);
        });
    }

    protected function registerChannelMake(): void
    {
        $this->app->singleton(ChannelMakeCommand::class, static function ($app) {
            return new ChannelMake($app['files']);
        });
    }

    protected function registerComponentMake(): void
    {
        $this->app->singleton(ComponentMakeCommand::class, static function ($app) {
            return new ComponentMake($app['files']);
        });
    }

    protected function registerControllerMake(): void
    {
        $this->app->singleton(ControllerMakeCommand::class, static function ($app) {
            return new ControllerMake($app['files']);
        });
    }

    protected function registerEventMake(): void
    {
        $this->app->singleton(EventMakeCommand::class, static function ($app) {
            return new EventMake($app['files']);
        });
    }

    protected function registerExceptionMake(): void
    {
        $this->app->singleton(ExceptionMakeCommand::class, static function ($app) {
            return new ExceptionMake($app['files']);
        });
    }

    protected function registerJobMake(): void
    {
        $this->app->singleton(JobMakeCommand::class, static function ($app) {
            return new JobMake($app['files']);
        });
    }

    protected function registerListenerMake(): void
    {
        $this->app->singleton(ListenerMakeCommand::class, static function ($app) {
            return new ListenerMake($app['files']);
        });
    }

    protected function registerMailMake(): void
    {
        $this->app->singleton(MailMakeCommand::class, static function ($app) {
            return new MailMake($app['files']);
        });
    }

    protected function registerMiddlewareMake(): void
    {
        $this->app->singleton(MiddlewareMakeCommand::class, static function ($app) {
            return new MiddlewareMake($app['files']);
        });
    }

    protected function registerModelMake(): void
    {
        $this->app->singleton(ModelMakeCommand::class, static function ($app) {
            return new ModelMake($app['files']);
        });
    }

    protected function registerNotificationMake(): void
    {
        $this->app->singleton(NotificationMakeCommand::class, static function ($app) {
            return new NotificationMake($app['files']);
        });
    }

    protected function registerObserverMake(): void
    {
        $this->app->singleton(ObserverMakeCommand::class
            , static function ($app) {
            return new ObserverMake($app['files']);
        });
    }

    protected function registerPolicyMake(): void
    {
        $this->app->singleton(PolicyMakeCommand::class, static function ($app) {
            return new PolicyMake($app['files']);
        });
    }

    protected function registerProviderMake(): void
    {
        $this->app->singleton(ProviderMakeCommand::class, static function ($app) {
            return new ProviderMake($app['files']);
        });
    }

    protected function registerRequestMake(): void
    {
        $this->app->singleton(RequestMakeCommand::class, static function ($app) {
            return new RequestMake($app['files']);
        });
    }

    protected function registerResourceMake(): void
    {
        $this->app->singleton(ResourceMakeCommand::class, static function ($app) {
            return new ResourceMake($app['files']);
        });
    }

    protected function registerRuleMake(): void
    {
        $this->app->singleton(RuleMakeCommand::class, static function ($app) {
            return new RuleMake($app['files']);
        });
    }

    protected function registerScopeMake(): void
    {
        $this->app->singleton(ScopeMakeCommand::class, static function ($app) {
            return new ScopeMake($app['files']);
        });
    }

    protected function registerTestMake(): void
    {
        $this->app->singleton(TestMakeCommand::class, static function ($app) {
            return new TestMake($app['files']);
        });
    }


    protected function registerMigrationMake(): void
    {
        $this->app->singleton('migration.creator', function ($app) {
            return new Migration($app['files'], $app->basePath('stubs'));
        });

        $this->app->singleton(MigrateMakeCommand::class, function ($app) {
            $creator = $app['migration.creator'];
            $composer = $app['composer'];
            return new MigrationMake($creator, $composer);
        });
    }

    protected function registerFactoryMake(): void
    {
        $this->app->singleton(FactoryMakeCommand::class, static function ($app) {
            return new FactoryMake($app['files']);
        });
    }

    protected function registerSeederMake(): void
    {
        $this->app->singleton(SeederMakeCommand::class, static function ($app) {
            return new SeederMake($app['files']);
        });
    }

    protected function registerSeed(): void
    {
        $this->app->singleton(SeedCommand::class, function ($app) {
            return new Seed($app['db']);
        });
    }


    protected function registerStubPublish(): void
    {
        $this->app->singleton(StubPublishCommand::class, StubPublish::class);
    }

    protected function registerModelShow(): void
    {
        $this->app->singleton(ShowModelCommand::class, ModelShow::class);
    }

    protected function registerModelPrune(): void
    {
        $this->app->singleton(PruneCommand::class, ModelPrune::class);
    }
}
