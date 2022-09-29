<?php

declare(strict_types=1);

namespace JazzTest\Modules;

use Illuminate\Support\Facades\Config;

class ConfigMergeTest extends ATestCase
{
    protected array $myConfig = [
        'name' => 'NewModuleName',
        'info' => [
            'test' => 'Layers',
        ]
    ];


    /**
     * @dataProvider provider
     */
    public function testRun(string $key, string $context, string $module): void
    {
        Config::set($key, array_merge(
            $this->myConfig, Config::get($key, [])
        ));

        $arr = Config::get('modules');

        $this->assertEquals('NewModuleName', Config::get($key . '.name'));
        $this->assertEquals('NewModuleName', $arr['contexts'][$context][$module]['name']);

        $this->assertEquals('Layers', Config::get($key . '.info.test'));
        $this->assertEquals('Layers', $arr['contexts'][$context][$module]['info']['test']);
    }
    public function provider(): array
    {
        return [
            ['modules.contexts.default.NewModule', 'default', 'NewModule'],
            ['modules.default.NewModule', 'default', 'NewModule'],
            ['modules.NewModule', 'default', 'NewModule'],

            ['modules.contexts.sample.NewModule', 'sample', 'NewModule'],
            ['modules.sample.NewModule', 'sample', 'NewModule'],
        ];
    }
}
