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
            ['MyPivotModel', null, ['--pivot' => true]],
            ['MyMorphPivotModel', null, ['--morph-pivot' => true]],
            ['MyFactoryModel', null, ['--factory' => true]],
            ['MyMigrationModel', null, ['--migration' => true]],
            ['MySeedModel', null, ['--seed' => true]],
            ['MyControllerModel', null, ['--controller' => true]],
            ['MyResourceModel', null, ['--resource' => true]],
            ['MyPolicyModel', null, ['--policy' => true]],
            ['MyApiModel', null, ['--api' => true]],
            ['MyAllModel', null, ['--all' => true]],

            ['MyModel', self::MODULE, []],
            ['MyPivotModel', self::MODULE, ['--pivot' => true]],
            ['MyMorphPivotModel', self::MODULE, ['--morph-pivot' => true]],
            ['MyFactoryModel', self::MODULE, ['--factory' => true]],
            ['MyMigrationModel', self::MODULE, ['--migration' => true]],
            ['MySeedModel', self::MODULE, ['--seed' => true]],
            ['MyControllerModel', self::MODULE, ['--controller' => true]],
            ['MyResourceModel', self::MODULE, ['--resource' => true]],
            ['MyPolicyModel', self::MODULE, ['--policy' => true]],
            ['MyModelApi', self::MODULE, ['--api' => true]],
            ['MyAllModel', self::MODULE, ['--all' => true]],

            ['MyModel', 'sample.Sandbox', []],
            ['MyPivotModel', 'sample.Sandbox', ['--pivot' => true]],
            ['MyMorphPivotModel', 'sample.Sandbox', ['--morph-pivot' => true]],
            ['MyFactoryModel', 'sample.Sandbox', ['--factory' => true]],
            ['MyMigrationModel', 'sample.Sandbox', ['--migration' => true]],
            ['MySeedModel', 'sample.Sandbox', ['--seed' => true]],
            ['MyControllerModel', 'sample.Sandbox', ['--controller' => true]],
            ['MyResourceModel', 'sample.Sandbox', ['--resource' => true]],
            ['MyPolicyModel', 'sample.Sandbox', ['--policy' => true]],
            ['MyModelApi', 'sample.Sandbox', ['--api' => true]],
            ['MyAllModel', 'sample.Sandbox', ['--all' => true]],
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

        $this->assertionsFactory($class, $args, $module);
        $this->assertionsMigration($args, $module);
        $this->assertionsSeeders($class, $args, $module);
        $this->assertionsController($class, $args, $module);
        $this->assertionsPolicies($class, $args, $module);
    }

    protected function assertionsFactory(string $class, array $args, ?string $module): void
    {
        if (isset($args['--factory'])) {
            ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);

            $path = self::SANDBOX . '/';
            if ($module) {
                $path .= $meta['path'] . '/' . $module . '/' . $meta['assets'] . '/' . $meta['factories']['path'] . '/';
            } else {
                $path .= 'database/factories/';
            }
            $path .= Str::after($class, 'Models\\') . 'Factory.php';
            $this->assertFileExists($path, 'FACTORY not found');
        }
    }

    protected function assertionsMigration(array $args, ?string $module): void
    {
        if (isset($args['--migration'])) {
            ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);

            $path = $this->app->basePath() . '/';
            if ($module) {
                $path .= $meta['path'] . '/' . $module . '/' . $meta['assets'] . '/' . $meta['migrations'] . '/';
            } else {
                $path .= 'database/migrations/';
            }
            $path .= '*_table.php';

            $files = $this->app['files']->glob($path);
            $this->assertGreaterThanOrEqual(1, $files, 'MIGRATION not found: ' . $path);
        }
    }

    protected function assertionsSeeders(string $class, array $args, ?string $module): void
    {
        if (isset($args['--seed'])) {
            ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);

            $path = self::SANDBOX . '/';
            if ($module) {
                $path .= $meta['path'] . '/' . $module . '/' . $meta['assets'] . '/' . $meta['seeders']['path'] . '/';
            } else {
                $path .= 'database/seeders/';
            }
            $path .= Str::after($class, 'Models\\') . 'Seeder.php';

            $this->assertFileExists($path, 'SEEDER not found');
        }
    }

    protected function assertionsController(string $class, array $args, ?string $module): void
    {
        if (isset($args['--controller']) || isset($args['--resource']) || isset($args['--api'])) {
            ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);

            $path = self::SANDBOX . '/';
            if ($module) {
                $path .= $meta['path'] . '/' . $module . '/';
            } else {
                $path = self::APP_PATH . '/';
            }
            $path .= 'Http/Controllers/' . Str::after($class, 'Models\\') . 'Controller.php';

            $this->assertFileExists($path, 'CONTROLLER not found');
        }
    }

    protected function assertionsPolicies(string $class, array $args, ?string $module): void
    {
        if (isset($args['--policy'])) {
            ['name' => $module, 'meta' => $meta] = $this->getMyModule($module);

            $path = self::SANDBOX . '/';
            if ($module) {
                $path .= $meta['path'] . '/' . $module . '/';
            } else {
                $path = self::APP_PATH . '/';
            }
            $path .= 'Policies/' . Str::after($class, 'Models\\') . 'Policy.php';

            $this->assertFileExists($path, 'POLICY not found');
        }
    }
}
