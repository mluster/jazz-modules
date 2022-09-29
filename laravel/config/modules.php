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
    |--------------------------------------------------------------------------
    | Must exist in the "contexts" list
    */
    'context' => env('MODULES_CONTEXT', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Module Contexts List
    |--------------------------------------------------------------------------
    */
    'contexts' => [
        'default' => [
            '_meta' => [
                /*
                |--------------------------------------------------------------
                | If Module is ACTIVE (bool)
                |--------------------------------------------------------------
                */
                'active' => env('MODULES_DEFAULT_ACTIVE', true),
                /*
                |--------------------------------------------------------------
                | Autoload Context Provider
                |--------------------------------------------------------------
                | - if TRUE, system will load ALL Context Providers at startup
                | - if FALSE, manually add Module Providers in APP Config OR
                |       in Primary Context Provider
                */
                'autoload' => env('MODULES_DEFAULT_AUTOLOAD', false),

                /*
                |--------------------------------------------------------------
                | Context Namespace
                |--------------------------------------------------------------
                */
                'namespace' => env('MODULES_DEFAULT_NAMESPACE', 'Module\\'),
                /*
                |--------------------------------------------------------------
                | Path to Context (relative to Application base path)
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
                | Module ASSETS Directory Name
                |--------------------------------------------------------------
                */
                'assets' => env('MODULES_DEFAULT_ASSETS', 'assets'),
                /*
                |--------------------------------------------------------------
                | Modules VIEWS Directory Name (within ASSETS)
                |--------------------------------------------------------------
                */
                'views' => env('MODULES_DEFAULT_VIEWS', 'views'),
                /*
                |--------------------------------------------------------------
                | Module (Database) MIGRATIONS Directory Name (within ASSETS)
                |--------------------------------------------------------------
                */
                'migrations' => env('MODULES_DEFAULT_MIGRATIONS', 'database/migrations'),
                /*
                |--------------------------------------------------------------
                | Module (Database) FACTORIES
                |--------------------------------------------------------------
                */
                'factories' => [
                    /*
                    |----------------------------------------------------------
                    | FACTORIES Path (within ASSETS)
                    |----------------------------------------------------------
                    */
                    'path' => env('MODULES_DEFAULT_FACTORIES_PATH', 'database/factories'),
                    /*
                    |----------------------------------------------------------
                    | FACTORIES Namespace
                    |----------------------------------------------------------
                    */
                    'namespace' => env('MODULES_DEFAULT_FACTORIES_NAMESPACE', 'Database\\Factories\\'),
                ],
                /*
                |--------------------------------------------------------------
                | Module (Database) SEEDERS
                |--------------------------------------------------------------
                */
                'seeders' => [
                    /*
                    |----------------------------------------------------------
                    | SEEDERS Path (within ASSETS)
                    |----------------------------------------------------------
                    */
                    'path' => env('MODULES_DEFAULT_SEEDERS_PATH', 'database/seeders'),
                    /*
                    |----------------------------------------------------------
                    | SEEDERS Namespace
                    |----------------------------------------------------------
                    */
                    'namespace' => env('MODULES_DEFAULT_SEEDERS_NAMESPACE', 'Database\\Seeders\\'),
                ],
            ],
        ],
        'sample' => [
            '_meta' => [
                'active' => true,
                'autoload' => false,
                'namespace' => 'Sample\\',
                'path' => 'sample',
                'provider' => 'Provider',
                'assets' => 'assets',
                'views' => 'views',
                'migrations' => 'database/migrations',
                'factories' => [
                    'path' => 'database/factories',
                    'namespace' => 'Database\\Factories\\',
                ],
                'seeders' => [
                    'path' => 'database/seeders',
                    'namespace' => 'Database\\Seeders\\',
                ],
            ],
        ],
    ],
];
