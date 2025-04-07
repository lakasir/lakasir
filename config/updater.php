<?php

return [
    'url' => 'https://api.github.com/repos/lakasir/lakasir/releases/latest',
    'artisan_after_update' => [
        'migrate' => [
            '--force' => true,
            '--path' => 'database/migrations/tenant',
        ],
        'db:seed' => [
            '--class' => 'PermissionSeeder',
        ],
        'optimize:clear',
        'optimize',
    ],
    'artisan_after_restore' => [
        'migrate:rollback' => [
            '--force' => true,
            '--path' => 'database/migrations/tenant',
        ],
        'db:seed' => [
            '--class' => 'PermissionSeeder',
        ],
        'optimize:clear',
        'optimize',
    ],
    'commands_after_update' => [
        'npm install',
        'npm run build'
    ]
];
