<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure the `id` column is AUTO_INCREMENT on environments
        // where it was created without it, but **skip** this on SQLite
        // which does not support the MySQL-style `MODIFY` syntax used
        // in the raw statement below (this was breaking our test suite,
        // which runs on SQLite).
        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'sqlite' && Schema::hasTable('job_application_status_histories')) {
            DB::statement('ALTER TABLE job_application_status_histories MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't try to revert the AUTO_INCREMENT change automatically.
        // Leaving this empty is safe.
    }
};


