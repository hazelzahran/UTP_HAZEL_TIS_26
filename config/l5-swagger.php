<?php

return [
    'api' => [
        /*
        |--------------------------------------------------------------------------
        | Edit to set the api's title
        |--------------------------------------------------------------------------
        */

        'title' => 'WowoClean API Documentation',

        /*
        |--------------------------------------------------------------------------
        | A description of the API
        |--------------------------------------------------------------------------
        */
        'description' => 'RESTful API untuk Sistem Manajemen Limbah B3 - WowoClean dengan autentikasi JWT, role-based access control, dan gateway architecture.',

        /*
        |--------------------------------------------------------------------------
        | The version of the API
        |--------------------------------------------------------------------------
        */
        'version' => env('API_VERSION', 'v1.0.0'),

        /*
        |--------------------------------------------------------------------------
        | Set this to `true` in development mode so that docs are regenerated on each request
        |--------------------------------------------------------------------------
        */
        'in_memory' => env('APP_DEBUG', false),
    ],

    'routes' => [
        /*
        |--------------------------------------------------------------------------
        | Specify the routes where you want to expose the docs
        |--------------------------------------------------------------------------
        */

        'api' => 'api/documentation',
        'docs' => 'docs',
    ],

    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Specify the paths where files are located
        |--------------------------------------------------------------------------
        */

        'docs_json' => 'api-docs.json',
        'docs_yaml' => 'api-docs.yaml',
        'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', false),

        /*
        |--------------------------------------------------------------------------
        | Specify the paths where annotations are stored
        |--------------------------------------------------------------------------
        */
        'annotations' => [
            base_path('app'),
        ],

    ],

    'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', true),

    'scanOptions' => [
        'default_processors_configuration' => [],
        'aliases' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Uncomment to add constants which can be used in annotations
    |--------------------------------------------------------------------------
    */
    'constants' => [
        'LARAVEL_VERSION' => \Illuminate\Foundation\Application::VERSION,
    ],

];
