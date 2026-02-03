<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

echo "Dropping migrations table...\n";

try {
    DB::statement('DROP TABLE IF EXISTS migrations');
    echo "Dropped migrations table.\n";
} catch (\Throwable $e) {
    echo "Note: " . $e->getMessage() . "\n";
}

// Remove orphaned .ibd file (XAMPP default data dir)
$dataDir = 'C:\\xampp\\mysql\\data\\fortresslendersdb';
$ibd = $dataDir . DIRECTORY_SEPARATOR . 'migrations.ibd';
if (file_exists($ibd)) {
    if (@unlink($ibd)) {
        echo "Removed orphaned migrations.ibd.\n";
    } else {
        echo "Could not remove migrations.ibd (file may be in use). Stop MySQL, delete it manually, then run: php artisan migrate\n";
        exit(1);
    }
}

echo "Running php artisan migrate...\n";

Artisan::call('migrate', ['--force' => true]);

echo Artisan::output();
echo "Done.\n";
