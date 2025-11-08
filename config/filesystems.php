<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        'public_uploads_id' => [
            'driver' => 'local',
            'root' => public_path('id-photo'),
            'url' => env('APP_URL').'/id-photo',
            'visibility' => 'public',
            'throw' => false,
        ],

        'public_uploads_resorts' => [
            'driver' => 'local',
            'root' => public_path('resorts-photo'),
            'url' => env('APP_URL').'/resorts-photo',
            'visibility' => 'public',
            'throw' => false,
        ],

        'public_uploads_payments' => [
            'driver' => 'local',
            'root' => public_path('payments-photo'),
            'url' => env('APP_URL').'/payments-photo',
            'visibility' => 'public',
            'throw' => false,
        ],

        'public_uploads_lost_item' => [
            'driver' => 'local',
            'root' => public_path('lost-item-photo'),
            'url' => env('APP_URL').'/lost-item-photo',
            'visibility' => 'public',
            'throw' => false,
        ],

        'public_uploads_accommodations' => [
            'driver' => 'local',
            'root' => public_path('accommodations-photo'),
            'url' => env('APP_URL').'/accommodations-photo',
            'visibility' => 'public',
            'throw' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
