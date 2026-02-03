<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Checking database...\n";

$tables = DB::select("SHOW TABLES");
$dbName = Schema::getConnection()->getDatabaseName();
$key = 'Tables_in_' . $dbName;
$tableList = array_map(fn ($r) => $r->$key, $tables);
echo "Tables: " . implode(', ', $tableList) . "\n\n";

// Add user_id to team_members if table exists
if (in_array('team_members', $tableList)) {
    $cols = DB::select("SHOW COLUMNS FROM team_members LIKE 'user_id'");
    if (empty($cols)) {
        DB::statement('ALTER TABLE team_members ADD COLUMN user_id BIGINT UNSIGNED NULL AFTER id');
        DB::statement('ALTER TABLE team_members ADD CONSTRAINT team_members_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
        echo "Added user_id to team_members.\n";
    } else {
        echo "team_members.user_id already exists.\n";
    }
} else {
    echo "Table team_members does not exist; skipping.\n";
}

// Add user_id to candidates if table exists
if (in_array('candidates', $tableList)) {
    $cols = DB::select("SHOW COLUMNS FROM candidates LIKE 'user_id'");
    if (empty($cols)) {
        DB::statement('ALTER TABLE candidates ADD COLUMN user_id BIGINT UNSIGNED NULL AFTER id');
        DB::statement('ALTER TABLE candidates ADD CONSTRAINT candidates_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
        echo "Added user_id to candidates.\n";
    } else {
        echo "candidates.user_id already exists.\n";
    }
} else {
    echo "Table candidates does not exist; skipping.\n";
}

echo "\nDone.\n";
