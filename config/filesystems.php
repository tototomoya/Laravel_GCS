<?php
return [
    'default' => env('FILESYSTEM_DRIVER', 'local'),
    'cloud' => env('FILESYSTEM_CLOUD', 's3'),
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => '/home/vagrant/laravel/laravel/storage/app',
        ],
        'public' => [
            'driver' => 'local',
            'root' => '/home/vagrant/laravel/laravel/storage/app/public',
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'gcs' => [
            'driver' => 'gcs',
            'project_id' => 'true-upgrade-276805',
            'key_file' => '/home/vagrant/laravel/laravel/gcloud_key.json',
            'bucket' => 'laravel_tomoya',
        ],
    ],
    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
