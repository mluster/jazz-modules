<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ImplicitRule;
use Illuminate\Contracts\Validation\InvokableRule;

class RuleMakeTest extends ATestCase
{
    protected string $myCommand = 'make:rule';
    protected string $myComponent = 'Rules';

    public function provider(): array
    {
        return [
            ['MyRule', null, null],
            ['MyInvokableRule', null, ['--invokable' => true]],
            ['MyInvokableImplicitRule', null, ['--invokable' => true, '--implicit' => true]],

            ['MyRule', self::MODULE, null],
            ['MyInvokableRule', self::MODULE, ['--invokable' => true]],
            ['MyInvokableImplicitRule', self::MODULE, ['--invokable' => true, '--implicit' => true]],
        ];
    }

    protected function assertions(string $name, ?string $module): void
    {
        parent::assertions($name, $module);

        $class = $this->getMyClass($name, $module);

        $args = $this->myArgs;
        if (array_key_exists('--invokable', $args)) {
            $this->assertTrue(is_subclass_of($class, InvokableRule::class));
        } else {
            $this->assertTrue(is_subclass_of($class, Rule::class));
        }
    }
}
