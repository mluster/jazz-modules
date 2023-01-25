<?php

declare(strict_types=1);

namespace JazzTest\Modules\Database;

use JazzTest\Modules\Console\SeederMakeTest;

class SeedTest extends SeederMakeTest
{

    /**
     * @dataProvider provider
     */
    public function testRun(string $name, ?string $module, ?array $args): void
    {
        parent::testRun($name, $module, $args);

        $args = ($args ?? []);

        if ($name !== 'DatabaseSeeder') {
            $args['--class'] = $name;
        }
        $args['--no-interaction'] = true;
        if ($module) {
            $args[$this->myModuleKey] = $module;
        }

        $this->artisan('db:seed', $args)
            ->assertExitCode(0);
    }

    public function provider(): array
    {
        return [
            ['DatabaseSeeder', null, []],
            ['DatabaseSeeder', self::MODULE, []],
            ['DatabaseSeeder', 'sample.Sandbox', []],

            ['MySeedSeeder', null, []],
            ['MySeedSeeder', self::MODULE, []],
            ['MySeedSeeder', 'sample.Sandbox', []],
        ];
    }
}