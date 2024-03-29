<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

use Illuminate\Contracts\Queue\ShouldQueue;

class ListenerMakeTest extends ATestCase
{

    protected string $myCommand = 'make:listener';
    protected string $myComponent = 'Listeners';

    public function provider(): array
    {
        return [
            ['MyListener', null, ['--event' => null, '--queued' => false], [], []],
            ['MyEventListener', null, ['--event' => 'MyListenerEvent', '--queued' => false], [], []],
            ['MyQueuedListener', null, ['--event' => null, '--queued' => true], [], []],
            ['MyQueuedEventListener', null, ['--event' => 'MyQueuedListenerEvent', '--queued' => true], [], []],

            ['MyListener', self::MODULE, ['--event' => null, '--queued' => false], [], []],
            ['MyEventListener', self::MODULE, ['--event' => 'MyListenerEvent', '--queued' => false], [], []],
            ['MyQueuedListener', self::MODULE, ['--event' => null, '--queued' => true], [], []],
            ['MyQueuedEventListener', self::MODULE, ['--event' => 'MyQueuedListenerEvent', '--queued' => true], [], []],

            ['MyListener', self::SAMPLE_MODULE, ['--event' => null, '--queued' => false], [], []],
            ['MyEventListener', self::SAMPLE_MODULE, ['--event' => 'MyListenerEvent', '--queued' => false], [], []],
            ['MyQueuedListener', self::SAMPLE_MODULE, ['--event' => null, '--queued' => true], [], []],
            ['MyQueuedEventListener', self::SAMPLE_MODULE, ['--event' => 'MyQueuedListenerEvent', '--queued' => true], [], []],

            ['App\\Listeners\\MyFullListener', null, ['--event' => null, '--queued' => false], ['MyFullListener'], ['MyFullListener']],
            ['App\\Listeners\\MyFullEventListener', null, ['--event' => 'App\\Events\\MyFullListenerEvent', '--queued' => false], ['MyFullEventListener'], ['MyFullEventListener']],
        ];
    }

    protected function assertions(string $name, ?string $module, array $myFile, array $myClass): void
    {
        $args = $this->myArgs;
        parent::assertions($name, $module, $myFile, $myClass);

        $class = $this->getMyClass($name, $module);
        $implements = is_subclass_of($class, ShouldQueue::class);
        $this->assertTrue($args['--queued'] ? $implements : !$implements);
        $this->assertMethodInClass($class, 'handle', true);
    }
}
