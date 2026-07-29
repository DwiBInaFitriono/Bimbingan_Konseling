<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$sessions = Illuminate\Support\Facades\DB::table('sessions')->get();
foreach($sessions as $s) {
    echo "ID: " . $s->id . "\n";
    $payload = unserialize(base64_decode($s->payload));
    echo "CSRF Token: " . ($payload['_token'] ?? 'none') . "\n";
}
