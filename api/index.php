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

require __DIR__ . '/../public/index.php';
