<?php

declare(strict_types=1);

namespace Jazz\Modules;

use Illuminate\Config\Repository;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class ConfigRepository extends Repository
{
    protected function parseKey(string $key): string
    {
        if (
            Str::startsWith($key, 'modules.') &&
            !Str::startsWith($key, [
                'modules.key',
                'modules.name',
                'modules.context',
                'modules.contexts',
            ])
        ) {
            [, $context] = explode('.', $key);
            if (parent::has('modules.contexts.' . $context)) {
                $key = Str::replaceFirst('modules.', 'modules.contexts.', $key);
            } else {
                $key = Str::replaceFirst('modules.', 'modules.contexts.default.', $key);
            }
        }
        return $key;
    }

    public function has($key): bool
    {
        return parent::has($this->parseKey($key));
    }
    public function get($key, $default = null)
    {
        if (is_array($key)) {
            return $this->getMany($key);
        }
        return parent::get($this->parseKey($key), $default);
    }
    public function getMany($keys)
    {
        $ret = [];
        foreach ($keys as $key => $default) {
            if (is_numeric($key)) {
                [$key, $default] = [$default, null];
            } else if (is_string($key)) {
                $key = $this->parseKey($key);
            }
            $ret[$key] = Arr::get($this->items, $key, $default);
        }
        return $ret;
    }
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];
        foreach ($keys as $key => $value) {
            Arr::set($this->items, $this->parseKey($key), $value);
        }
    }
}
