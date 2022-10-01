<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

trait TModuleContext
{
    /**
     * @return array{context: ?string, name: ?string, meta: ?array{namespace: string, path: string, provider: string, assets: string, active: bool, autoload: bool}}
     */
    protected function getModule(): array
    {
        $ret = [
            'context' => null,
            'name' => null,
            'meta' => null,
        ];

        $module = $this->option(Config::get('modules.key'));
        if ($module) {
            $context = Config::get('modules.context');
            if (Str::contains($module, '.')) {
                [$context, $module] = explode('.', $module);
            }

            $ret['context'] = $context;
            $ret['name'] = $module;
            $ret['meta'] = Config::get('modules.contexts.' . $context . '._meta');
        }

        return $ret;
    }
}
