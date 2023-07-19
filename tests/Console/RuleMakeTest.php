<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

use Illuminate\Contracts\Validation\ValidationRule;
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
            ['MyImplicitRule', null, ['--implicit' => true]],

            ['MyRule', self::MODULE, null],
            ['MyImplicitRule', self::MODULE, ['--implicit' => true]],

            ['MyRule', 'sample.Sandbox', null],
            ['MyImplicitRule', 'sample.Sandbox', ['--implicit' => true]],
        ];
    }

    protected function assertions(string $name, ?string $module): void
    {
        parent::assertions($name, $module);

        $class = $this->getMyClass($name, $module);
        $this->assertTrue(is_subclass_of($class, ValidationRule::class));
    }
}