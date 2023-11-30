<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Console\EventGenerateCommand;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Jazz\Modules\AEventProvider;

class EventGenerate extends EventGenerateCommand
{
    public function handle()
    {
        $providers = $this->laravel->getProviders(EventServiceProvider::class);
        foreach ($providers as $provider) {
            $module = ($provider instanceof AEventProvider)
                ? $provider->getContext() . '.' . $provider->getModule()
                : null;

            foreach ($provider->listens() as $event => $listeners) {
                $this->makeEventAndListeners($event, $listeners, $module);
            }
        }
    }

    protected function makeEventAndListeners($event, $listeners, ?string $module = null)
    {
        if (!str_contains($event, '\\')) {
            return;
        }

        $args = ['name' => $event];
        if ($module !== null) {
            $args['--' . Config::get('modules.key')] = $module;
        }

        $this->callSilent('make:event', $args);
        $this->makeListeners($event, $listeners);
    }

    protected function makeListeners($event, $listeners, ?string $module = null)
    {
        foreach ($listeners as $listener) {
            $listener = preg_replace('/@.+$/', '', $listener);

            $args = [
                'name' => $listener,
                '--event' => $event
            ];
            if ($module !== null) {
                $args['--' . Config::get('modules.key')] = $module;
            }

            $this->callSilent('make:listener', array_filter($args));
        }
    }
}
