<?php

declare(strict_types=1);

namespace Jazz\Modules\Console;

use Illuminate\Database\Console\ShowModelCommand;

class ModelShow extends ShowModelCommand
{
    use TGenerator;
}
