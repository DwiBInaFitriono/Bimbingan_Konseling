<?php

// Ensure caching directories exist in the writable /tmp directory for Vercel
$dirs = [
    '/tmp/storage/cache',
    '/tmp/storage/logs',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/app/public',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Forward Vercel requests to normal index.php
require __DIR__ . '/../public/index.php';
