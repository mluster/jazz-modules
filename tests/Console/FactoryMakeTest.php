<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class FactoryMakeTest extends ATestCase
{
    protected string $myCommand = 'make:factory';
    protected string $myComponent = 'Database.Factories';

    public function provider(): array
    {
        return [
            ['MyUser', null, []],
            ['My.User', null, []],

            ['MyUser', self::MODULE, []],
            ['My.User', self::MODULE, []],

            ['MyUser', 'sample.Sandbox', []],
            ['My.User', 'sample.Sandbox', []],
        ];
    }

    protected function assertions(string $name, ?string $module): void
    {
        parent::assertions($name, $module);

        $class = $this->getMyClass($name, $module);
        $this->assertTrue(
            is_subclass_of($class, Factory::class, true),
            'Does not extend ' . Factory::class
        );
    }


    // HELPER METHODS
    protected function getMyPath(string $className, ?string $module): string
    {
        ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);

        $className = str_replace(['.', '\\'], '/', Str::finish($className, 'Factory'));

        $path = $this->app->basePath() . '/';
        if ($module) {
            $path .= $meta['path'] . '/' . $module . '/' . $meta['assets'] . '/' . $meta['factories']['path'] . '/';
        } else {
            $path .= 'database/factories/';
        }
        $path .= $className . '.php';

        return $path;
    }

    protected function getMyClass(string $className, ?string $module): string
    {
        ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);

        $className = str_replace(['.', '/'], '\\', $className);
        if ($module) {
            $className = $meta['namespace'] . $module . '\\' . $meta['factories']['namespace'] . $className;
        } else {
            $className = parent::getMyClass($className, $module);
            $className = Str::replaceFirst('App\\Database', 'Database', $className);
        }
        return Str::finish($className, 'Factory');
    }
}
