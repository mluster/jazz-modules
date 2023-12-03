<?php

declare(strict_types=1);

namespace JazzTest\Modules\Database;

use JazzTest\Modules\Console\SeederMakeTest;

class SeedTest extends SeederMakeTest
{

    /**
     * @dataProvider provider
     */
    public function testRun(string $name, ?string $module, ?array $args, array $myFile, array $myClass): void
    {
        parent::testRun($name, $module, $args, $myFile, $myClass);

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
            ['DatabaseSeeder', null, [], [], []],
            ['DatabaseSeeder', self::MODULE, [], [], []],
            ['DatabaseSeeder', self::SAMPLE_MODULE, [], [], []],

            ['MySeedSeeder', null, [], [], []],
            ['MySeedSeeder', self::MODULE, [], [], []],
            ['MySeedSeeder', self::SAMPLE_MODULE, [], [], []],
        ];
    }
}
