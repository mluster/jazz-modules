<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SeederMakeTest extends ATestCase
{
    protected string $myCommand = 'make:seeder';
    protected string $myComponent = 'Database.Seeders';

    public function provider(): array
    {
        return [
            ['MySeeder', null, null, [], []],
            ['MySeeder', self::MODULE, null, [], []],
            ['MySeeder', self::SAMPLE_MODULE, null, [], []],
        ];
    }

    protected function assertions(string $name, ?string $module, array $myFile, array $myClass): void
    {
        parent::assertions($name, $module, $myFile, $myClass);

        $class = $this->getMyClass($name, $module);
        $this->assertTrue(
            is_subclass_of($class, Seeder::class, true),
            'Does not extend ' . Seeder::class
        );
    }


    // HELPER METHODS
    protected function getMyPath(string $file, ?string $module): string
    {
        ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);

        $file = str_replace(['.', '\\'], '/', Str::finish($file, 'Seeder'));

        $path = $this->app->basePath() . '/';
        if ($module) {
            $path .= $meta['path'] . '/' . $module . '/' . $meta['assets'] . '/' . $meta['seeders']['path'] . '/';
        } else {
            $path .= 'database/seeders/';
        }
        $path .= $file . '.php';

        return $path;
    }

    protected function getMyClass(string $className, ?string $module): string
    {
        ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);

        $className = str_replace(['.', '/'], '\\', $className);
        if ($module) {
            $className = $meta['namespace'] . $module . '\\' . $meta['seeders']['namespace'] . $className;
        } else {
            $className = parent::getMyClass($className, $module);
            $className = Str::replaceFirst('App\\Database', 'Database', $className);
        }

        return Str::finish($className, 'Seeder');
    }
}
