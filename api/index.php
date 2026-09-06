<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
ini_set('display_errors', '0');

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

// Pastikan SSL cert TiDB Cloud tersedia di /tmp/ (writable di Vercel)
$sslCertDestination = '/tmp/tidb-ca.pem';
if (!file_exists($sslCertDestination)) {
    $possibleSslPaths = [
        __DIR__ . '/../config/ssl/tidb-ca.pem',
        __DIR__ . '/config/ssl/tidb-ca.pem',
    ];
    foreach ($possibleSslPaths as $sourcePath) {
        if (file_exists($sourcePath)) {
            @copy($sourcePath, $sslCertDestination);
            break;
        }
    }
}

// Override MYSQL_ATTR_SSL_CA ke path /tmp/ jika cert berhasil di-copy
if (file_exists($sslCertDestination)) {
    putenv('MYSQL_ATTR_SSL_CA=' . $sslCertDestination);
    $_ENV['MYSQL_ATTR_SSL_CA'] = $sslCertDestination;
}

require __DIR__ . '/../public/index.php';
