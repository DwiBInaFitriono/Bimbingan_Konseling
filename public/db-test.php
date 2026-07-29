<?php
header('Content-Type: text/plain');

$host = getenv('DB_HOST') ?: $_ENV['DB_HOST'] ?? 'unknown';
$port = getenv('DB_PORT') ?: $_ENV['DB_PORT'] ?? 4000;
$db   = getenv('DB_DATABASE') ?: $_ENV['DB_DATABASE'] ?? 'unknown';
$user = getenv('DB_USERNAME') ?: $_ENV['DB_USERNAME'] ?? 'unknown';
$pass = getenv('DB_PASSWORD') ?: $_ENV['DB_PASSWORD'] ?? 'unknown';
$ca   = getenv('MYSQL_ATTR_SSL_CA') ?: $_ENV['MYSQL_ATTR_SSL_CA'] ?? '';

echo "Host: $host\n";
echo "Port: $port\n";
echo "DB: $db\n";
echo "User: $user\n";
echo "CA Path: $ca\n";
echo "CA File Exists? " . (file_exists($ca) ? 'Yes' : 'No') . "\n";

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_TIMEOUT => 3, // 3 seconds timeout for testing
];

if ($ca) {
    $options[PDO::MYSQL_ATTR_SSL_CA] = $ca;
    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
}

try {
    echo "Connecting...\n";
    $start = microtime(true);
    $pdo = new PDO($dsn, $user, $pass, $options);
    $time = microtime(true) - $start;
    echo "Connected successfully in {$time}s!\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
