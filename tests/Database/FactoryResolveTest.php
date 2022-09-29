<?php

declare(strict_types=1);

namespace JazzTest\Modules\Database;

use Illuminate\Support\Str;
use JazzTest\Modules\ATestCase;
use Illuminate\Database\Eloquent\Factories\Factory as LaravelFactory;

class FactoryResolveTest extends ATestCase
{
    /**
     * @dataProvider provider
     */
    public function testRun(string $name, ?string $module, string $factory, string $model): void
    {
        $args = [
            'name' => $name,
            '--factory' => true,
            '--no-interaction' => true,
        ];
        if ($module) {
            $args['--module'] = $module;
        }
        $this->createArtisan('make:model', $args);
        if (!$module) {
            $path = 'database/factories/' . Str::replace('.', '/', $name) . 'Factory.php';
            require_once($this->app->basePath($path));
        }

        $this->assertEquals($factory, LaravelFactory::resolveFactoryName($model));

        $obj = new $factory();
        $this->assertEquals($model, $obj->modelName());
    }
    public function provider(): array
    {
        return [
            ['MyResolve', null, 'Database\\Factories\\MyResolveFactory', 'App\\Models\\MyResolve'],
            ['My.Resolve', null, 'Database\\Factories\\My\\ResolveFactory', 'App\\Models\\My\\Resolve'],
            ['MyResolve', 'Sandbox', 'Module\\Sandbox\\Database\\Factories\\MyResolveFactory', 'Module\\Sandbox\\Models\\MyResolve'],
            ['My.Resolve', 'Sandbox', 'Module\\Sandbox\\Database\\Factories\\My\\ResolveFactory', 'Module\\Sandbox\\Models\\My\\Resolve'],
            ['MyResolve', 'sample.Sandbox', 'Sample\\Sandbox\\Database\\Factories\\MyResolveFactory', 'Sample\\Sandbox\\Models\\MyResolve'],
            ['My.Resolve', 'sample.Sandbox', 'Sample\\Sandbox\\Database\\Factories\\My\\ResolveFactory', 'Sample\\Sandbox\\Models\\My\\Resolve'],
        ];
    }
}
