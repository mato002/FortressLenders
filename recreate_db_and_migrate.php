<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

$dbName = Config::get('database.connections.mysql.database');

echo "Recreating database '{$dbName}'...\n";

try {
    DB::statement("DROP DATABASE IF EXISTS `{$dbName}`");
    DB::statement("CREATE DATABASE `{$dbName}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    DB::statement("USE `{$dbName}`");
    echo "Database recreated.\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Running php artisan migrate...\n";

Artisan::call('migrate', ['--force' => true]);

echo Artisan::output();
echo "Done.\n";
