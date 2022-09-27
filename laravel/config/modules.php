<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Console Options KEY for Module
    |--------------------------------------------------------------------------
    | php artisan my:command --module=...
    */
    'key' => env('MODULES_KEY', 'module'),
    /*
    |--------------------------------------------------------------------------
    | Formal Name used for Descriptions/Comments
    |--------------------------------------------------------------------------
    */
    'name' => env('MODULES_NAME', 'Module'),
    /*
    |--------------------------------------------------------------------------
    | Default Context Name
    | - must exist in "contexts" list
    |--------------------------------------------------------------------------
    */
    'context' => env('MODULES_CONTEXT', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Available Module Contexts
    |--------------------------------------------------------------------------
    */
    'contexts' => [
        'default' => [
            '_meta' => [
                /*
                |--------------------------------------------------------------
                | Modules Namespace
                |--------------------------------------------------------------
                */
                'namespace' => env('MODULES_DEFAULT_NAMESPACE', 'Module\\'),
                /*
                |--------------------------------------------------------------
                | Path to Modules (relative to Application base path)
                |--------------------------------------------------------------
                */
                'path' => env('MODULES_DEFAULT_BASE', 'modules'),
                /*
                |--------------------------------------------------------------
                | Primary Context Provider
                |--------------------------------------------------------------
                */
                'provider' => env('MODULES_DEFAULT_PROVIDER', 'Provider'),

                /*
                |--------------------------------------------------------------
                | ASSETS Directory Name
                |--------------------------------------------------------------
                */
                'assets' => env('MODULES_DEFAULT_ASSETS', 'assets'),
                /*
                |--------------------------------------------------------------
                | VIEWS Directory Name (within ASSETS)
                |--------------------------------------------------------------
                */
                'views' => env('MODULES_DEFAULT_VIEWS', 'views'),
                /*
                |--------------------------------------------------------------
                | (Database) MIGRATIONS Directory Name (within ASSETS)
                |--------------------------------------------------------------
                */
                'migrations' => env('MODULES_DEFAULT_MIGRATIONS', 'migrations'),
                /*
                |--------------------------------------------------------------
                | (Database) FACTORIES
                |--------------------------------------------------------------
                */
                'factories' => [
                    /*
                    |----------------------------------------------------------
                    | FACTORIES Path (within ASSETS)
                    |----------------------------------------------------------
                    */
                    'path' => env('MODULES_DEFAULT_FACTORIES_PATH', 'factories'),
                    /*
                    |----------------------------------------------------------
                    | FACTORIES Namespace
                    |----------------------------------------------------------
                    */
                    'namespace' => env('MODULES_DEFAULT_FACTORIES_NAMESPACE', 'Factories\\'),
                ],
                /*
                |--------------------------------------------------------------
                | (Database) SEEDERS
                |--------------------------------------------------------------
                */
                'seeders' => [
                    /*
                    |----------------------------------------------------------
                    | SEEDERS Path (within ASSETS)
                    |----------------------------------------------------------
                    */
                    'path' => env('MODULES_DEFAULT_SEEDERS_PATH', 'seeders'),
                    /*
                    |----------------------------------------------------------
                    | SEEDERS Namespace
                    |----------------------------------------------------------
                    */
                    'namespace' => env('MODULES_DEFAULT_SEEDERS_NAMESPACE', 'Seeders\\'),
                ],

                /*
                |--------------------------------------------------------------
                | If Module is ACTIVE (bool)
                |--------------------------------------------------------------
                */
                'active' => env('MODULES_DEFAULT_ACTIVE', true),
                /*
                |--------------------------------------------------------------
                | Autoload Module Providers
                | - if TRUE, system will load ALL Module Providers at startup
                | - if FALSE, manually add Module Providers in APP Config OR
                |       in Primary Context Provider
                |--------------------------------------------------------------
                */
                'autoload' => env('MODULES_DEFAULT_AUTOLOAD', false),
            ],
        ],
    ],
];
