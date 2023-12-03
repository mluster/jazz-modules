<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

class PolicyMakeTest extends ATestCase
{

    protected string $myCommand = 'make:policy';
    protected string $myComponent = 'Policies';

    public function provider(): array
    {
        return [
            ['MyPolicy', null, ['--model' => null], [], []],
            ['MyModelPolicy', null, ['--model' => 'MyFakeModel'], [], []],

            ['MyPolicy', self::MODULE, ['--model' => null], [], []],
            ['MyModelPolicy', self::MODULE, ['--model' => 'MyFakeModel'], [], []],

            ['MyPolicy', self::SAMPLE_MODULE, ['--model' => null], [], []],
            ['MyModelPolicy', self::SAMPLE_MODULE, ['--model' => 'MyFakeModel'], [], []],
        ];
    }

    protected function assertions(string $name, ?string $module, array $myFile, array $myClass): void
    {
        $args = $this->myArgs;
        parent::assertions($name, $module, $myFile, $myClass);

        $class = $this->getMyClass($name, $module);
        $hasModel = $args['--model'] !== null;
        $this->assertMethodInClass($class, 'viewAny', $hasModel);
        $this->assertMethodInClass($class, 'view', $hasModel);
        $this->assertMethodInClass($class, 'create', $hasModel);
        $this->assertMethodInClass($class, 'update', $hasModel);
        $this->assertMethodInClass($class, 'delete', $hasModel);
        $this->assertMethodInClass($class, 'restore', $hasModel);
        $this->assertMethodInClass($class, 'forceDelete', $hasModel);

        $this->assertIsArray($args);
    }
}
