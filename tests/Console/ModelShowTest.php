<?php

declare(strict_types=1);

namespace JazzTest\Modules\Console;

class ModelShowTest extends ModelMakeTest
{
    public function provider(): array
    {
        return [
            ['MyModelShow', null, null],
            ['MyModelShow', self::MODULE, null],
        ];
    }

    /**
     * @param string $name
     * @param string|null $module
     * @param array|null $args
     * @return void
     * @dataProvider provider
     */
    public function testRun(string $name, ?string $module, ?array $args): void
    {
        $this->markTestIncomplete('ModelShowTest requires a DB with Model Migration. Not available at this time');
        /*parent::testRun($name, $module, $args);

        $args = [
            'model' => $name,
            '--json' => true,
            '--no-interaction' => true,
        ];
        if ($module) {
            $args[$this->myModuleKey] = $module;
        }

        $question = 'Inspecting database information requires the Doctrine DBAL (doctrine/dbal) package. Would you like to install it?';

        $this->artisan('model:show', $args)
            ->expectsQuestion($question, false)
            ->assertExitCode(0)
            ->expectsOutputToContain($name);*/
    }
}
