<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Foundation\Console\ShowModelCommand;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class ModelShow extends ShowModelCommand
{
    use TGenerator {
        qualifyModel as myQualifyModel;
    }
    use TSignature;

    public function __construct(Composer $composer = null)
    {
        $this->appendSignature();
        parent::__construct($composer);
    }

    protected function qualifyModel(string $model): string
    {
        if (str_contains($model, '\\') && class_exists($model)) {
            return $model;
        }

        $model = $this->myQualifyModel($model);
        if (
            Str::startsWith($model, 'App\\') &&
            !$this->option(Config::get('modules.key')) &&
            !is_dir(app_path('Models'))
        ) {
            $model = str_replace('Models\\', '', $model);
        }
        return $model;
    }

}
