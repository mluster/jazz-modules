<?php

namespace Jazz\Modules;

use Illuminate\Support\Reflector;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class DiscoverEvents
{
    public static function within(string $listenerPath, string $basePath, string $namespace): array
    {
        $listeners = collect(static::getListenerEvents(
            (new Finder)->files()->in($listenerPath), $basePath, $namespace
        ));
        $discoveredEvents = [];
        foreach ($listeners as $listener => $events) {
            foreach ($events as $event) {
                if (! isset($discoveredEvents[$event])) {
                    $discoveredEvents[$event] = [];
                }
                $discoveredEvents[$event][] = $listener;
            }
        }
        return $discoveredEvents;
    }

    public static function getListenerEvents(iterable $listeners, string $basePath, string $namespace): array
    {
        $listenerEvents = [];
        foreach ($listeners as $listener) {
            try {
                $listener = new ReflectionClass(
                    static::classFromFile($listener, $basePath, $namespace)
                );
            } catch (ReflectionException) {
                continue;
            }
            if (! $listener->isInstantiable()) {
                continue;
            }
            foreach ($listener->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if ((! Str::is('handle*', $method->name) && ! Str::is('__invoke', $method->name)) ||
                    ! isset($method->getParameters()[0])) {
                    continue;
                }
                $listenerEvents[$listener->name.'@'.$method->name] =
                                Reflector::getParameterClassNames($method->getParameters()[0]);
            }
        }
        return array_filter($listenerEvents);
    }

    public static function classFromFile(SplFileInfo $file, string $basePath, string $namespace): string
    {
        $class = Str::replaceFirst($basePath, '', $file->getRealPath());
        $class = trim($class, DIRECTORY_SEPARATOR);
        $class = Str::replaceLast('.php', '', $class);
        return $namespace . Str::replace(DIRECTORY_SEPARATOR, '\\', $class);
    }
}
