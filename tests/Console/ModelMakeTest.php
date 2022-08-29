<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Support\Str;
use Illuminate\Testing\PendingCommand;

class ModelMakeTest extends ATestCase
{
    protected string $myCommand = 'make:model';
    protected string $myComponent = 'Models';

    public function provider(): array
    {
        return [
            ['MyModel', null, []],
            ['MyModelPivot', null, ['--pivot' => null]],
            ['MyModelMorphPivot', null, ['--morph-pivot' => null]],
            ['MyModelFactory', null, ['--factory' => null]],
            ['MyModelMigration', null, ['--migration' => null]],
            ['MyModelSeed', null, ['--seed' => null]],
            ['MyModelController', null, ['--controller' => null]],
            ['MyModelResource', null, ['--resource' => null]],
            ['MyModelPolicy', null, ['--policy' => null]],
            ['MyModelApi', null, ['--api' => null]],
            ['MyModelAll', null, ['--all' => null]],

            ['MyModel', self::MODULE, []],
            ['MyModelPivot', self::MODULE, ['--pivot' => null]],
            ['MyModelMorphPivot', self::MODULE, ['--morph-pivot' => null]],
            ['MyModelFactory', self::MODULE, ['--factory' => null]],
            ['MyModelMigration', self::MODULE, ['--migration' => null]],
            ['MyModelSeed', self::MODULE, ['--seed' => null]],
            ['MyModelController', self::MODULE, ['--controller' => null]],
            ['MyModelResource', self::MODULE, ['--resource' => null]],
            ['MyModelPolicy', self::MODULE, ['--policy' => null]],
            ['MyModelApi', self::MODULE, ['--api' => null]],
            ['MyModelAll', self::MODULE, ['--all' => null]],
        ];
    }

    protected function createArtisan(string $command, array $args = []): PendingCommand
    {
        $question = 'Model does not exist. Do you want to generate it?';

        $artisan = parent::createArtisan($command, $args);
        if (
            array_key_exists('--resource', $args) ||
            array_key_exists('--api', $args) ||
            array_key_exists('--all', $args)
        ) {
            $artisan->expectsConfirmation($question, 'no');
        }
        return $artisan;
    }


    protected function assertions(string $name, ?string $module): void
    {
        $args = $this->myArgs;
        parent::assertions($name, $module);

        $class = $this->getMyClass($name, $module);
        $subclass = Model::class;
        if (isset($args['--pivot'])) {
            $subclass = Pivot::class;
        }
        if (isset($args['--morph-pivot'])) {
            $subclass = MorphPivot::class;
        }
        $this->assertTrue(is_subclass_of($class, $subclass, true), 'Does not extend ' . $subclass);

        if (isset($args['--all'])) {
            $args['--factory'] = true;
            $args['--seed'] = true;
            $args['--migration'] = true;
            $args['--controller'] = true;
            $args['--resource'] = true;
            $args['--policy'] = true;
        }

        $this->assertionsFactory($class, $args);
        $this->assertionsMigration($args);
        $this->assertionsSeeders($class, $args);
        $this->assertionsController($class, $args);
        $this->assertionsPolicies($class, $args);
    }

    protected function assertionsFactory(string $class, array $args): void
    {
        if (isset($args['--factory'])) {
            $path = self::SANDBOX;
            if ($args[$this->myModuleKey]) {
                $path = $this->myModulePath;
            }
            $path .= '/database/factories/' . Str::after($class, 'Models\\') . 'Factory.php';
            $this->assertFileExists($path, 'FACTORY not found');
        }
    }

    protected function assertionsMigration(array $args): void
    {
        if (isset($args['--migration'])) {
            $path = $this->app->basePath();
            if ($args[$this->myModuleKey]) {
                $path = $this->myModulePath . '/' . $args[$this->myModuleKey];
            }
            $path .= '/database/migrations/*_table.php';
            $files = $this->app['files']->glob($path);
            $this->assertCount(1, $files, 'MIGRATION not found');
        }
    }

    protected function assertionsSeeders(string $class, array $args): void
    {
        if (isset($args['--seed'])) {
            $path = self::SANDBOX;
            if ($args[$this->myModuleKey]) {
                $path = $this->myModulePath;
            }
            $path .= '/database/seeders/' . Str::after($class, 'Models\\') . 'Seeder.php';
            $this->assertFileExists($path, 'SEEDER not found');
        }
    }

    protected function assertionsController(string $class, array $args): void
    {
        if (isset($args['--controller']) || isset($args['--resource']) || isset($args['--api'])) {
            $path = self::APP_PATH;
            if ($args[$this->myModuleKey]) {
                $path = $this->myModulePath;
            }
            $path .= '/Http/Controllers/' . Str::after($class, 'Models\\') . 'Controller.php';
            $this->assertFileExists($path, 'CONTROLLER not found');
        }
    }

    protected function assertionsPolicies(string $class, array $args): void
    {
        if (isset($args['--policy'])) {
            $path = self::SANDBOX;
            if ($args[$this->myModuleKey]) {
                $path = $this->myModulePath;
            }
            $path .= '/Policies/' . Str::after($class, 'Models\\') . 'Policy.php';
            $this->assertFileExists($path, 'POLICY not found');
        }
    }
}
