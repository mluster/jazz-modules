<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Support\Facades\Config;

trait TSignature
{
    protected function appendSignature(): void
    {
        $key = Config::get('modules.key');
        $name = Config::get('modules.name');

        $signature = '{--' . $key . '= : ' . sprintf('Install in %s', $name) . '}';
        $this->signature .= $signature;
    }
}
