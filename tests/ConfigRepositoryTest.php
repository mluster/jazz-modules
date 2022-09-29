<?php

declare(strict_types=1);

namespace JazzTest\Modules;

use Illuminate\Support\Facades\Config;

class ConfigRepositoryTest extends ATestCase
{
    public function testFullContextsRetrieval()
    {
        $this->assertEquals('modules', Config::get('modules.contexts.default._meta.path'));
        $this->assertEquals('sample', Config::get('modules.contexts.sample._meta.path'));
    }
    public function testDirectContextRetrieval()
    {
        $this->assertEquals('modules', Config::get('modules.default._meta.path'));
        $this->assertEquals('sample', Config::get('modules.sample._meta.path'));
    }
    public function testAssumedDefaultContextRetrieval()
    {
        $this->assertEquals('modules', Config::get('modules._meta.path'));
    }
}
