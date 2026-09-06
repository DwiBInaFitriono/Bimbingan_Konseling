<?php

$requiredCacheDirectories = [
    '/tmp/storage/cache',
    '/tmp/storage/logs',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/app/public',
    '/tmp/bootstrap/cache',
];

foreach ($requiredCacheDirectories as $cacheDirectoryPath) {
    if (!is_dir($cacheDirectoryPath)) {
        @mkdir($cacheDirectoryPath, 0777, true);
    }
}

putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');

require __DIR__ . '/../public/index.php';
