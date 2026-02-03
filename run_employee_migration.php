<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Running employee migration (user_id on team_members and candidates)...\n";

$driver = Schema::getConnection()->getDriverName();
$dbName = Schema::getConnection()->getDatabaseName();

// team_members
if (Schema::hasTable('team_members')) {
    if (! Schema::hasColumn('team_members', 'user_id')) {
        Schema::table('team_members', function ($table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });
        echo "Added user_id to team_members.\n";
    } else {
        echo "team_members.user_id already exists.\n";
    }
} else {
    echo "Table team_members does not exist; skipping. Run php artisan migrate when your DB is ready.\n";
}

// candidates
if (Schema::hasTable('candidates')) {
    if (! Schema::hasColumn('candidates', 'user_id')) {
        Schema::table('candidates', function ($table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });
        echo "Added user_id to candidates.\n";
    } else {
        echo "candidates.user_id already exists.\n";
    }
} else {
    echo "Table candidates does not exist; skipping.\n";
}

echo "Done.\n";
